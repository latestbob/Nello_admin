<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Specialization;
use App\MedSchedule;
use Carbon\Carbon;
use App\Models\Appointment;


class HealthCenterController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        if($search == "active"){
            $healthCenters = HealthCenter::where("is_active",true)->paginate($size);
        }

        elseif($search =="inactive"){
            $healthCenters = HealthCenter::where("is_active",false)->paginate($size);
        }

      else {
        $healthCenters = HealthCenter::when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or phone like ? or email like ? or city like ? or state like ? or address1 like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                ]
            );

        })->paginate($size);
      }


        return view('health-centers', compact('healthCenters', 'search', 'size'));
    }

    public function viewHealthCenter(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/health-centers')->with('error', "Health Center ID missing");
        }

        $healthCenter = HealthCenter::where('uuid', $request->uuid)->first();

        //dd($healthCenter);

        if (empty($healthCenter)) {
            return redirect('/health-centers')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any health center");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('health_centers', 'email')->ignore($healthCenter->id)],
                'phone' => 'required',
                'address1' => 'nullable|string',
           
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'nullable'
            ])->validate();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->uploadFile($request, 'logo');
            }

            // $healthCenter->update($data);
            $healthCenterr = HealthCenter::where('uuid', $request->uuid)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address1' => $request->address1,
                'state' => $request->state,
                'city' => $request->city,
                'fee' => $request->fee
            ]);

            return redirect("/health-centers")->with('success', "Health center has been updated successfully");

        }

        // $response = Http::get('http://locationsng-api.herokuapp.com/api/v1/states');

        // $states =  $response->json();
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            
        ])->get('https://api.facts.ng/v1/states');
         $states =  $response->json();
         
        return view('health-center-view', compact('healthCenter', 'uuid','states'));
    }

    public function addHealthCenter(Request $request) {

        if (strtolower($request->method()) == "post") {

            $data = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:255|unique:health_centers,email',
                'phone' => 'required|digits_between:11,16|unique:health_centers,phone',
                'address1' => 'required|string',
                'address2' => 'nullable|string',
                'state' => 'required|string',
                'city'  => 'required|string',
                'center_type'  => 'required|alpha|min:3',
                'logo' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'required|numeric'
            ])->validate();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->uploadFile($request, 'logo');
            }

            $data['vendor_id'] = $request->user()->vendor_id;
            $data['uuid'] = Str::uuid()->toString();
            HealthCenter::create($data);

            return redirect("/health-centers")->with('success', "Health Center has been added successfully");

        }

        // $response = Http::get('https://api.facts.ng/v1/states');

        // $states =  $response->json();

        // return view('health-center-add',compact('states'));
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            
        ])->get('https://api.facts.ng/v1/states');
         $states =  $response->json();

        return view('health-center-add',compact('states'));
    }

    public function changeStatus(Request $request) {

        if (empty($request->uuid)) {
            return response([
                'status' => false,
                'message' => "Health Center ID missing"
            ]);
        }

        $healthCenter = HealthCenter::where('uuid', $request->uuid)->first();

        if (empty($healthCenter)) {
            return response([
                'status' => false,
                'message' => "Sorry, the ID '{$request->uuid}' is associated with any health center"
            ]);
        }

        if (!$healthCenter->update(['is_active' => !$healthCenter->is_active])) {
            return response([
                'status' => false,
                'message' => "Sorry, we could not " . ($healthCenter->is_active == true ? 'activate' : 'deactivate') . " this health center at this time."
            ]);
        }

        return response([
            'status' => true,
            'message' => "This health center is now " . ($healthCenter->is_active == true ? 'active' : 'inactive')
        ]);
    }


    //go to spec schedule page


    public function specschedule($uuid){
        $healthCenter = HealthCenter::where('uuid',$uuid)->first();

        $spec = Specialization::where('med_center_uuid',$uuid)->get();

        $schedule = MedSchedule::where('med_uuid',$uuid)->get();


        return view('health-center-specschedule',compact('healthCenter','spec','schedule'));
        
    }

    //add specialization 


    public function addspec(Request $request,$uuid){

        $this->validate($request ,[
            'specialization' => 'required'
        ]);

        $existed = Specialization::where('med_center_uuid',$uuid)->where('specialization',$request->specialization)->exists();

        if($existed){
            return back()->with('error','Specialization already exists for this Medical Center');
        }

        $healthCenter = HealthCenter::where('uuid', $uuid)->exists();

        if($healthCenter){
         

            $spec = new Specialization;
            $spec->med_center_uuid = $uuid;
            $spec->specialization = $request->specialization;
            $spec->save();

            return back()->with('success', "Specialization added successfully");
        }

    }


    //delete specialization


    public function deletespec($id){
        $spec = Specialization::find($id)->delete();

        return back()->with('success','Specialization Removed successfully');

        
    }

    //add schedule healthcenter

    public function addschedule(Request $request,$uuid){
        $this->validate($request,[
            'specialization' => 'required',
            'day' => 'required',
            'time' => 'required'
            
        ]);


        $state = HealthCenter::where('uuid',$uuid)->value('state');
        $lga = HealthCenter::where('uuid',$uuid)->value('city');
 
        $schedule = new MedSchedule;
        
        $schedule->med_uuid = $uuid;
        $schedule->day = $request->day;
        $schedule->time = $request->time;
        $schedule->state= $state;
        $schedule->lga = $lga;
        $schedule->specialization=$request->specialization;

        $schedule->save();

        return back()->with('success','Schedule added successfully');

        
    }

    //delete medical center schedule


    public function deleteschedule($id){
        $schedule = MedSchedule::find($id)->delete();

        return back()->with('success','Schedule removed successfully');

        
        
    }

     // nello website facility calendar days

     public function nellofacilitycalendardays($uuid){
        
        $days = MedSchedule::where("med_uuid",$uuid)->distinct()->get(['day']);

        //return response()->json($days);
        $output = [];

        foreach($days as $number){
            $day_of_week = (int) date('N', strtotime($number["day"]));

            array_push($output,$day_of_week);
        }

        return $output;
    }

    //Nello website medical center appointment booked time for medical centers

    public function getmedicalappointmenttime(Request $request){
        $validator = Validator::make($request->all(), [
            'specialization'=> "required",
            'uuid' => 'required|exists:health_centers',
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
  //$doctor_id = User::where("id",$request->uuid)->value("id");

  //return $dayname;

  $all_time = MedSchedule::where("med_uuid",$request->uuid)->where('day',$dayname)->distinct()->get(['time']);

               $all_time_array = [];

               foreach($all_time as $time){
                array_push($all_time_array,$time);
               }


               //return $all_time_array;

               $today = date('d-m-Y');

               $date_format = Carbon::parse($request->date)->format('Y-m-d');
 
               $booked_time = Appointment::where("center_uuid",$request->uuid)->where("date",$date_format)->distinct()->get(['time']);
 
               $booked_time_array = [];
 
               foreach($booked_time as $booked){
                   array_push($booked_time_array, $booked);
               }

               //return $booked_time_array;



              //med_time

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

                 //return $diff;
                 return  array_slice($diff, 0, 9);
              
             }

             else{
                return  array_slice($diff, 0, 9);
             }

             //get array of available the

             




    }



}
