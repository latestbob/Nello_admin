<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {
        $search = $request->search;
        $gender = $request->gender;
        $size = empty($request->size) ? 10 : $request->size;

        $doctors = User::with('prescriptions')->where('user_type', 'doctor')
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

        return view('doctors', compact('doctors', 'search', 'gender', 'size'));
    }

    public function viewDoctor(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/doctors')->with('error', "Doctor ID missing");
        }

        $doctor = User::with('prescriptions')->where(['user_type' => 'doctor', 'uuid' => $request->uuid])->first();

        if (empty($doctor)) {
            return redirect('/doctors')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any doctor account");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users', 'email')->ignore($doctor->id)],
                'phone' => ['required', 'digits_between:11,16',
                    Rule::unique('users', 'phone')->ignore($doctor->id)],
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

            $doctor->update($data);

            session()->put('success', "{$doctor->firstname}'s profile has been updated successfully");

        }

        return view('doctor-view', compact('doctor', 'uuid'));
    }

    public function addDoctor(Request $request) {

        if (strtolower($request->method()) == "post") {

            $data = Validator::make($request->all(), [
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|digits_between:11,16|unique:users,phone',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required_with:password|string|same:password',
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

            $data['password'] = Hash::make($data['password']);
            $data['vendor_id'] = $request->user()->vendor_id;
            $data['uuid'] = Str::uuid()->toString();
            $data['user_type'] = 'doctor';
            User::create($data);

            return redirect("/doctors")->with('success', "Doctor has been added successfully");

        }

        return view('doctor-add');
    }
}
