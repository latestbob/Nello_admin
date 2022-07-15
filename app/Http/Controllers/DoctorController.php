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
use App\Models\HealthCenter;
use Illuminate\Support\Facades\Http;

// Doctor Controller handle all doctors request

// This is handle every doctor api calls and request from the 
//Admin Backend.


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
                    "(firstname like ? or lastname like ? or phone like ? or email like ? or aos like ? or hospital like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
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
                'title' => 'required|string|max:20',
                'firstname' => 'required|string|max:50',
                'lastname'  => 'nullable|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users', 'email')->ignore($doctor->id)],
                
                    
                'dob' => 'nullable|date_format:Y-m-d|before_or_equal:today',
                'about' => 'nullable|string',
                'address' => 'nullable|string',
                'hospital' => 'nullable|string',
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                
                'gender' => 'required|string|in:Male,Female',
               
                'sponsor' => 'nullable|string',
                'aos' => 'nullable|string',
                // 'picture' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'nullable'
            ])->validate();

            if ($request->hasFile('picture')) {

                $data['picture'] = $this->uploadFile($request, 'picture');
//            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            if (!empty($data['dob'])) {
                $data['dob'] = Carbon::parse($data['dob'])->toDateString();
            }

            
            $doctorr = User::where(['user_type' => 'doctor', 'uuid' => $request->uuid])->update([
                'title'=> $request->title,
                'firstname'=>$request->firstname,
                'lastname'=>$request->lastname,
                'email' =>$request->email,
                'about' => $request->about,
                'gender' => $request->gender,
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'hospital'=>$request->hospital,
                'sponsor' =>$request->sponsor,
                'aos' => $request->aos,
                'fee' => $request->fee

            ]);
            session()->put('success', "Doctor's profile has been updated successfully");

        }

        return view('doctor-view', compact('doctor', 'uuid'));
    }

    public function addDoctor(Request $request) {

        if (strtolower($request->method()) == "post") {

            $data = Validator::make($request->all(), [
                'title' => 'required|string|max:20',
                'firstname' => 'required|string|max:50',
                'lastname'  => 'nullable|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|digits_between:11,16|unique:users,phone',
                'about' => 'nullable|string',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required_with:password|string|same:password',
                'dob' => 'nullable|date_format:Y-m-d|before_or_equal:today',
                'address' => 'nullable|string',
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                'religion' => 'nullable|string',
                'gender' => 'required|string|in:Male,Female',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'sponsor' => 'nullable|string',
                'aos' => 'nullable|string',
                'hospital' => 'required|string',
                'picture' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'required'
            ])->validate();

          

      if ($request->hasFile('picture')) {

             $data['picture'] = $this->uploadFile($request, 'picture');
           $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            if (!empty($data['dob'])) {
                $data['dob'] = Carbon::parse($data['dob'])->toDateString();
            }

            $data['password'] = Hash::make($data['password']);
            $data['vendor_id'] = $request->user()->vendor_id;
            $data['uuid'] = Str::uuid()->toString();
            $data['user_type'] = 'doctor';
                $data['fee'] = $request->fee;
            User::create($data);

          return redirect("/doctors")->with('success', "Doctor has been added successfully");

        }
        ///add here

        $healthcenter = HealthCenter::all();
        $response = Http::get('http://locationsng-api.herokuapp.com/api/v1/states');

       $states =  $response->json();
        return view('doctor-add',compact('healthcenter','states'));
    }

    public function changeStatus(Request $request) {

        if (empty($request->uuid)) {
            return response([
                'status' => false,
                'message' => "Doctor ID missing"
            ]);
        }

        $user = User::where(['uuid' => $request->uuid, 'user_type' => 'doctor'])->first();

        if (empty($user)) {
            return response([
                'status' => false,
                'message' => "Sorry, the ID '{$request->uuid}' is associated with any doctor"
            ]);
        }

        if (!$user->update(['active' => !$user->active])) {
            return response([
                'status' => false,
                'message' => "Sorry, we could not " . ($user->active == true ? 'activate' : 'deactivate') . " this doctor at this time."
            ]);
        }

        return response([
            'status' => true,
            'message' => "This doctor is now " . ($user->active == true ? 'active' : 'inactive')
        ]);
    }


    public function deleteDoctor($id){
        $user = User::find($id)->delete();

        return back()->with('success', "Doctor has removed successfully");
        
    }
}
