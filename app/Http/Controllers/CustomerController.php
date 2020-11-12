<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\User;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {
        $search = $request->search;
        $gender = $request->gender;
        $size = empty($request->size) ? 10 : $request->size;

        $customers = User::where('user_type', 'customer')
            ->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(firstname like ? or lastname like ? or phone like ? or email like ? or aos like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            })->when($gender, function ($query, $gender) {

                $query->where('gender', '=', "{$gender}");

            })->paginate($size);

        $pharmacies = Pharmacy::select(['id', 'name'])->get()->toJson();

        return view('customers', compact('customers', 'pharmacies', 'search', 'gender', 'size'));
    }

    public function viewCustomer(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/customers')->with('error', "Doctor ID missing");
        }

        $customer = User::where(['user_type' => 'customer', 'uuid' => $request->uuid])->first();

        if (empty($customer)) {
            return redirect('/customers')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any customer account");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users', 'email')->ignore($customer->id)],
                'phone' => ['required', 'digits_between:11,16',
                    Rule::unique('users', 'phone')->ignore($customer->id)],
                'dob' => 'required|date_format:Y-m-d|before_or_equal:today',
                'address' => 'nullable|string',
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                'religion' => 'nullable|string',
                'gender' => 'required|string|in:Male,Female',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'sponsor' => 'nullable|string',
                'aos' => 'nullable|string',
                'picture' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('picture')) {

                $data['picture'] = $this->uploadFile($request, 'picture');
//            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            if (!empty($data['dob'])) {
                $data['dob'] = Carbon::parse($data['dob'])->toDateString();
            }

            $customer->update($data);

            session()->put('success', "{$customer->firstname}'s profile has been updated successfully");

        }

        return view('customer-view', compact('customer', 'uuid'));
    }

    public function makeAgent(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'uuid' => 'required|uuid|exists:users,uuid',
            'id' => 'required|numeric|exists:pharmacies,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors(),
            ]);
        }

        $customer = User::where(['user_type' => 'customer', 'uuid' => $request->uuid])->first();

        if (!$customer->update(['user_type' => 'agent', 'pharmacy_id' => $request->id])) {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, we could not make this customer an agent at this time, please try again later',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Agent has been deleted successfully',
        ]);
    }
}
