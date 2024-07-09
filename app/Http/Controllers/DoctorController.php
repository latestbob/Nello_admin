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
use App\DocSchedule;
use App\Models\Appointment;
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


        if($search == "active"){
            $doctors = User::where('user_type','doctor')->where('active',true)->paginate($size);
        }

        elseif($search == "inactive"){
            $doctors = User::where('user_type','doctor')->where('active',false)->paginate($size);
        }

        else{

            $doctors = User::with('prescriptions')->where('user_type', 'doctor')
            ->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(firstname like ? or lastname like ? or phone like ? or email like ? or aos like ? or hospital like ? or city like ? or state like ? or sponsor like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            })->when($gender, function ($query, $gender) {

                $query->where('gender', '=', "{$gender}");

            })->paginate($size);
        }
  

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
                'fee' => 'nullable',
                'picture' => 'required'
            ])->validate();

//             if ($request->hasFile('picture')) {

//                 $data['picture'] = $this->uploadFile($request, 'picture');
// //            $data['image'] = 'http://www.famacare.com/img/famacare.png';

//             }

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
                'fee' => $request->fee,
                'picture' => $request->picture

            ]);
            session()->put('success', "Doctor's profile has been updated successfully");

        }

        // $response = Http::get('https://locationsng-api.herokuapp.com/api/v1/states');

        // $states =  $response->json();

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            
        ])->get('https://api.facts.ng/v1/states');
         $states =  $response->json();
         
        return view('doctor-view', compact('doctor', 'uuid', 'states'));
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
    //     $response = Http::get('http://locationsng-api.herokuapp.com/api/v1/states');

    //    $states =  $response->json();

    $response = Http::withoutVerifying()->withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        
    ])->get('https://api.facts.ng/v1/states');
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

    public function doctorschedule($uuid){
        $doctor = User::where('uuid',$uuid)->first();
        $schedule = DocSchedule::where('doc_uuid',$uuid)->get();
        return view('doctor-schedule',compact('doctor','schedule'));
    }


    public function doctorscheduleadd(Request $request,$uuid){
        $this->validate($request,[
            'specialization' => 'required',
            'day' => 'required',
            'time' => 'required'
            
        ]);

        $schedule= new DocSchedule;
        $schedule->doc_uuid = $uuid;
        $schedule->day = $request->day;
        $schedule->time = $request->time;
        $schedule->specialization = $request->specialization;

        $schedule->save();

        return back()->with('success','Schedule Created Successfully');
    }



    //doctor schedule delete

    public function doctorscheduledelete($id){
        $schedule = DocSchedule::find($id)->delete();

       return back()->with('success','Schedule removed successfully');
    }



    // nello website doctor calendar days

    public function nellodoctorscalendardays($uuid){
        
        $days = DocSchedule::where("doc_uuid",$uuid)->distinct()->get(['day']);

        //return response()->json($days);
        $output = [];

        foreach($days as $number){
            $day_of_week = (int) date('N', strtotime($number["day"]));

            array_push($output,$day_of_week);
        }

        return $output;
    }



    // nello website doctor booked time 
//     public function nellodoctortimes(Request $request){
//         $validator = Validator::make($request->all(), [
//             'specialization'=> "required",
//             'uuid' => 'required|exists:users',
//             'date'=> 'required|date_format:d-m-Y',
// ]);

//         if($validator->fails()){
//             return response([
//                 'status' => 'failed',
//                 'message' => $validator->errors()
//             ]);
//         }


//         $dayname = Carbon::parse($request->date)->format('l');

//         return $dayname;
//     }


public function getdoctorappointmenttime(Request $request){
    $validator = Validator::make($request->all(), [
                    'specialization'=> "required",
                    'uuid' => 'required|exists:users',
                    'date'=> 'required|date_format:d-m-Y',
        ]);
        
                if($validator->fails()){
                    return response([
                        'status' => 'failed',
                        'message' => $validator->errors()
                    ]);
                }
          date_default_timezone_set('Africa/Lagos');
        
                $dayname = Carbon::parse($request->date)->format('l');
                $doctor_id = User::where("id",$request->uuid)->value("id");
        
               // return $dayname;

               $all_time = DocSchedule::where("doc_uuid",$request->uuid)->where('day',$dayname)->distinct()->get(['time']);

               $all_time_array = [];

               foreach($all_time as $time){
                array_push($all_time_array,$time);
               }
           
              // return response()->json($all_time);

              //change date format 

              $today = date('d-m-Y');

              $date_format = Carbon::parse($request->date)->format('Y-m-d');

              $booked_time = Appointment::where("doctor_id",$doctor_id)->where("date",$date_format)->distinct()->get(['time']);

              $booked_time_array = [];

              foreach($booked_time as $booked){
                  array_push($booked_time_array, $booked);
              }

            //   return response()->json([
            //       'all_time' => $all_time,
            //       'booked_time' => $booked_time
            //   ]);

             $diff = array_diff(array_column($all_time_array, 'time'), array_column($booked_time_array, 'time'));
             $diff = array_values($diff);

             //return $today;

             if($request->date == $today){
                 //filter out passed time from diff array
               
                 $current_time = time();
                 foreach ($diff as $key => $time) {
                     if (strtotime($time) < $current_time) {
                         unset($diff[$key]);
                     }
                 }

                 return $diff;
              
             }

             else{
                 return $diff;
             }

             

           


}


//delete users

public function deleteUsers(Request $request, $email){
    $user = User::where("email",$email)->delete();

    return "user deleted";

  

}


//get doctor by Id
public function getdoctorbyid($id){
    $doctor = User::find($id);

    return response()->json($doctor);
}



}
