<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Order;
use App\Models\Pharmacies;
use App\Models\User;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PharmaciesController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;

        $pharmacies = Pharmacies::orderBy('name');

        if (!empty($search = $request->search)) {

            $pharmacies = $pharmacies->whereRaw(
                "(name like ? or address like ? or email like ? or phone like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%",
                ]
            );
        }

        $pharmacies = $pharmacies->paginate($size);

        return view('pharmacies', compact('pharmacies', 'size', 'search'));
    }

    public function addPharmacy(Request $request) {

        if (strtolower($request->method()) == "post") {

            $validated = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'address' => 'required|string|min:10|max:255',
                'email' => 'required|string|email|max:255|unique:pharmacies,email',
                'phone' => 'required|digits_between:11,16|unique:pharmacies,phone',
                'picture' => 'nullable|image|mimes:jpeg,jpg,png',
                'location' => 'nullable|numeric|exists:locations,id',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required_with:password|string|same:password',
                'is_pick_up_location' => 'required_without:location|boolean'
            ])->validate();

            if ($request->hasFile('picture')) {

                $validated['picture'] = $this->uploadFile($request, 'picture');
//            $validated['image'] = 'http://www.famacare.com/img/famacare.png';
            }

            $validated['password'] = Hash::make($validated['password']);
            $validated['uuid'] = Str::uuid()->toString();
            $validated['location_id'] = $validated['location'];
            unset($validated['location']);

            $validated['is_pick_up_location'] = (($validated['is_pick_up_location'] ?? 0) == 1 ? true : false);

            Pharmacies::create($validated);

            return redirect("/pharmacies")->with('success', "Pharmacy has been added successfully");
        }

        $locations = Locations::all();

        return view('pharmacy-add', compact('locations'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws ValidationException
     */
    public function viewPharmacy(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/pharmacies')->with('error', "Pharmacy ID missing");
        }

        $pharmacy = Pharmacies::where(['uuid' => $request->uuid])->first();

        if (empty($pharmacy)) {
            return redirect('/pharmacies')->with('error', "Sorry, the ID '{$request->uuid}' is associated with any Pharmacy");
        }

        if (strtolower($request->method()) == "post") {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'address' => 'required|string|min:10|max:255',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('pharmacies')->ignore($pharmacy->id)],
                'phone' => ['required', 'digits_between:11,16',
                    Rule::unique('pharmacies')->ignore($pharmacy->id)],
                'picture' => 'nullable|image|mimes:jpeg,jpg,png',
                'location' => 'nullable|numeric|exists:locations,id',
                'is_pick_up_location' => 'required_without:location|boolean'

            ]);

            if ($request->hasFile('picture')) {

                $validated['picture'] = $this->uploadFile($request, 'picture');
//            $validated['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            $validated = $validator->validate();

            $validated['location_id'] = $validated['location'];
            unset($validated['location']);

            $validated['is_pick_up_location'] = (($validated['is_pick_up_location'] ?? 0) == 1 ? true : false);

            if (!$validated['is_pick_up_location']) {

                $order = Order::where([['pickup_location_id', '=', $pharmacy->id], ['delivery_method', '=', 'pickup'],
                    ['payment_confirmed', '=', true], ['delivery_status', '=', false]])->first();

                if (!empty($order)) {
                    $validator->getMessageBag()->add('is_pick_up_location',
                        "You can't remove this pharmacy as a pickup location now. There are pending order(s) to be picked up from this pharmacy.");

                    throw new ValidationException($validator);
                }
            }

            $pharmacy->update($validated);

            return redirect('/pharmacies')->with('success', "Pharmacy has been updated successfully");
        }

        $locations = Locations::all();

        return view('pharmacy-view', compact('locations', 'pharmacy', 'uuid'));

    }

    public function deletePharmacy(Request $request) {

        if (!$request->uuid) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, missing pharmacy id',
            ]);
        }

        $delete = Pharmacies::where(['uuid' => $request->uuid])->first();

        if (!$delete->delete()) {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, we could not delete this pharmacy at this time, please try again later',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pharmacy has been deleted successfully',
        ]);

    }

    public function viewAgents(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        $agents = User::where('user_type', 'agent')->when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or phone like ? or email like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%"
                ]
            );

        })->paginate($size);

        return view('pharmacy-agents', compact('agents', 'search', 'size'));
    }

    public function deleteAgent(Request $request)
    {
        if (!$request->uuid) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, missing agent id',
            ]);
        }

        $agent = User::where(['user_type' => 'agent', 'uuid' => $request->uuid])->first();

        if (!$agent->update(['user_type' => 'customer'])) {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, we could not delete this agent at this time, please try again later',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Agent has been deleted successfully',
        ]);
    }
}