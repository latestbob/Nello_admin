<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use App\Notifications\NotifyCreatedRider;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


class RiderController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {

        $search = $request->search;
        $gender = $request->gender;
        $size = empty($request->size) ? 10 : $request->size;

        $riders = User::with('delivered')->where('user_type', 'rider')
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

        return view('riders', compact('riders', 'search', 'gender', 'size'));
    }

    public function viewRider(Request $request)
    {

        if (empty($uuid = $request->uuid)) {
            return redirect('/riders')->with('error', "Rider ID missing");
        }

        $rider = User::where(['user_type' => 'rider', 'uuid' => $request->uuid])->first();

        if (empty($rider)) {
            return redirect('/riders')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any rider account");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => [
                    'required', 'string', 'email', 'max:255',
                    Rule::unique('users', 'email')->ignore($rider->id)
                ],
                'phone' => [
                    'required', 'digits_between:11,16',
                    Rule::unique('users', 'phone')->ignore($rider->id)
                ],
                'location' => 'required|numeric|exists:locations,id',
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
                //$data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            if (!empty($data['dob'])) {
                $data['dob'] = Carbon::parse($data['dob'])->toDateString();
            }

            $data['location_id'] = $data['location'];

            $rider->update($data);

            session()->put('success', "{$rider->firstname}'s profile has been updated successfully");
        }

        $locations = Location::all();

        $response = Http::get('https://locationsng-api.herokuapp.com/api/v1/states');

        $states =  $response->json();

        return view('rider-view', compact('rider', 'locations', 'uuid','states'));
    }

    public function addRider(Request $request)
    {

        if (strtolower($request->method()) == "post") {

            $data = Validator::make($request->all(), [
                'username' => 'required|string|max:30|regex:/^[a-z0-9._-]+$/|not_in:admin,rider,agent,doctor|unique:users,username',
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|digits_between:11,16|unique:users,phone',
                'location' => 'required|numeric|exists:locations,id',
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

            $data['password'] = Hash::make("Rider@101");
            $data['vendor_id'] = $request->user()->vendor_id;
            $data['location_id'] = $data['location'];
            $data['uuid'] = Str::uuid()->toString();
            $data['user_type'] = 'rider';
            $rider = User::create($data);

            if (!$rider) {

                return redirect("/riders")->with('error', "Sorry, we couldn't create a rider at this time. Please try again later.");
            } else $rider->notify(new NotifyCreatedRider());

            return redirect("/riders")->with('success', "Rider has been added successfully");
        }

        $locations = Location::all();


        $response = Http::get('https://locationsng-api.herokuapp.com/api/v1/states');

        $states =  $response->json();
        return view('rider-add', compact('locations','states'));
    }

    public function deleteRider(Request $request)
    {

        if (!$request->uuid) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, missing rider id',
            ]);
        }

        $delete = User::where(['uuid' => $request->uuid, 'user_type' => 'rider'])->first();

        if (!$delete->delete()) {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, we could not delete this rider at this time, please try again later',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Rider has been deleted successfully',
        ]);
    }
}
