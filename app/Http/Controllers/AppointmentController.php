<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\HealthCenter;
use App\SpecialistSchedule;
use App\MedcenterSchedule;
use App\SpecialistTime;
use App\Specialization;
use App\MedcenterTime;
use App\MedicalReport;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        // $appointments = Appointment::with(['user', 'center', 'doctor'])
        //     ->whereHas('user', function ($query) use ($search) {

        //         $query->when($search, function ($query, $search) {

        //             $query->whereRaw(
        //                 "(firstname like ? or lastname like ? or phone like ? or email like ?)",
        //                 [
        //                     "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
        //                 ]
        //             );
        //         });

        //         // })->whereHas('center', function ($query) use ($search) {

        //         //     $query->when($search, function ($query, $search) {

        //         //         $query->whereRaw(
        //         //             "(name like ? or state like ? or phone like ? or email like ?)",
        //         //             [
        //         //                 "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
        //         //             ]
        //         //         );

        //         //     });

        //     })
        //     ->when($search, function ($query, $search) {

        //         $query->whereRaw(
        //             "(reason like ? or description like ? or center_uuid like ?)",
        //             [
        //                 "%{$search}%", "%{$search}%", "%{$search}%"
        //             ]
        //         );
        //     })
        //     ->orderBy('date', 'DESC')
        //     ->orderBy('time', 'DESC')
        //     ->paginate($size);

            $appointments = Appointment::with(['user', 'center', 'doctor'])
            ->whereHas('user', function($query) use ($search){
                $query->where('firstname', 'LIKE', '%'.$search.'%')
                      ->orWhere('lastname', 'LIKE', '%'.$search.'%')
                      ->orWhere('email', 'LIKE', '%'.$search.'%');
            }) 

            ->orWhereHas('doctor', function($query) use ($search){
                $query->where('firstname', 'LIKE', '%'.$search.'%')
                      ->orWhere('lastname', 'LIKE', '%'.$search.'%')
                      ->orWhere('email', 'LIKE', '%'.$search.'%');
            }) 
            ->orWhereHas('center', function($query) use ($search){
                $query->where('name', 'LIKE', '%'.$search.'%');
                     
            })
            ->orWhere( function($query) use ($search){
                $query->where('reason', 'LIKE', '%'.$search.'%')
                ->orWhere('time', 'LIKE', '%'.$search.'%')
                      ->orWhere('link', 'LIKE', '%'.$search.'%')
                      ->orWhere('status', 'LIKE', '%'.$search.'%');
                     
            })
            
            ->orderBy('id', 'DESC')
           
            ->paginate($size);


        return view('appointments', compact('appointments', 'search', 'size'));
    }

    //appointment vistation slip


    public function visitation($ref){

        $appointment = Appointment::where('ref_no',$ref)->first();
        
        $user = User::where('uuid',$appointment->user_uuid)->first();
        
        $center = HealthCenter::where('uuid',$appointment->center_uuid)->first();
        //dd($user->phone);
        $data = [
           'userphone' => $user->phone,
           'useremail' => $user->email,
           'useraddress' => $user->address,
           'username' => $user->firstname.' '.$user->lastname,
           'ref' => $ref,
           'centertype' => $center->center_type,
           'centername' => $center->name,
           'centeraddress' => $center->address1,
           'reason' => $appointment->reason,
           'datetime' => $appointment->date.' - '.$appointment->time,
           'fee' => $center->fee,
           'date' => date('Y-m-d'),

        ];
          
        $pdf = PDF::loadView('visitation',compact('data'));
        return $pdf->download('nello_visitation_slip.pdf');


    

    }

    //apppointment status update

    public function updatestatus(Request $request, $id){
        $appointment = Appointment::find($id)->update([
            'status' => 'completed'
        ]);

        return back()->with('success','Appointment status was updated successfully');

       
    }

    //update back to pending

    public function updatepending(Request $request, $id){
        $appointment = Appointment::find($id)->update([
            'status' => 'pending'
        ]);

        return back()->with('success','Appointment status was updated successfully');
    }


    //delete appointmnet

    public function deleteappointments(Request $request){
        $delete = DB::table("transaction_logs")->get();


        return $delete;
    }


    //appointment reschedule 
    public function appointmentreschedule($id){
        $appointment = Appointment::find($id);

       // dd($appointment);

       return view("reschedule",compact("appointment"));
    }

    //reschedule appointment admin

    public function rescheduleadmin(Request $request){
        
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $appointment = Appointment::where("ref_no",$request->ref_no)->update([
            'date' => $date,
            'time' => $request->time,
        ]);

        $latest = Appointment::where("ref_no",$request->ref_no)->first();
        //dd($latest->user->email);
        if($request->type == "center"){
            $resbooking = Http::withoutVerifying()->post('https://mw.asknello.com/api/facility/mail',[
                

                "centername" => $latest->center_name,
                "time" => $request->time,
                "date" => $date,
               
                "link"=> "https://admin.asknello.com/visitation/".$latest->ref_no,
                "useremail" => $latest->user->email
            
            ]);
        }

        elseif($request->type == "doctor"){


                    $customerdetails = [
            'doctor' => $latest->doctor->title.". ".$latest->doctor->firstname,
            'time' => $request->time,
            'date' => $date,
            'doctoraos' => $latest->doctor->aos,
            "link"=>$latest->link,
            'username' => $latest->user->firstname,
         
        ];



            $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/chabotemail',[
                "usermail" => $latest->user->email,
                "doctormail" => $latest->doctor->email,
                "data" => $customerdetails,
            ]);

        }


       
        //dd($appointment);


        
  

//    $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/chabotemail',[
//        "usermail" => $user->email,
//        "doctormail" => $doctor->email,
//        "data" => $customerdetails,
//    ]);

return back()->with("msg","Appointment rescheduled successfully");




    }


    //specialist calendar

    public function specialistcalendar($id){

        $doctor = User::find($id);

        $calender= SpecialistSchedule::where("doc_uuid",$doctor->uuid)->get();
        return view("specialistcalendar",compact("doctor","calender"));
    }

    //specialist calendar post

    public function specialistcalendarpost(Request $request){
         
        $month = date('m'); //uncomment this tomorrow 
       $monthstring = date("F", strtotime('m')); 
       
   
      
        $date = $request->mydates;

        $selected = explode(",", $date);


       foreach($selected as $select){
        $existed = SpecialistSchedule::where("doc_uuid",$request->doc_uuid)->where('date',$select)->where('specialization',$request->specialization)->exists();

        if($existed){
            return back()->with('error','Date(s) already exists in the calendar for ' .$request->specialization);
        } 

    
           $calender = new SpecialistSchedule;

           $calender->doc_uuid = $request->doc_uuid;
           $calender->specialization = $request->specialization;
           $calender->center = $request->center;
           $calender->date = $select;
           $calender->month = $month;
           $calender->monthstring = $monthstring;

           $calender->save();

           
       }

       return back()->with("msg",'Calandar created successfully');
   
        

    }

    //delete specialist calender 

    public function deletespecialistcalender(Request $request, $id){

        
       
        $delete = SpecialistSchedule::find($id);

        $time = SpecialistTime::where("date",$delete->date)->where("doc_uuid",$delete->doc_uuid)->delete();

        $delete = SpecialistSchedule::find($id)->delete();

        return back()->with("msg, Calendar schedule deleted successfully");
    }


    //specialist calendar time

    public function specialistcalendertime($id){
        $schedule = SpecialistSchedule::find($id);
        $availabletime = SpecialistTime::where("date",$schedule->date)->where("doc_uuid",$schedule->doc_uuid)->get();

        //dd($calender);

        return view("specialisttime",compact("schedule","availabletime"));
    }

    //specialist calender time post

    public function specialistcalendertimepost(Request $request){
        if(!$request->time){
            return back()->with("error","Kindly select at least a time");
        }
       
        $date = Carbon::createFromFormat('d/m/Y',$request->date)->format('l, F d, Y');
         //dd($date);
        foreach($request->time as $times){
            //dump($times);
    
            $existed = SpecialistTime::where("date",$request->date)->where("time",$times)->where("doc_uuid",$request->doc_uuid)->exists();
    
            if(!$existed){
                
                $specialisttime = new SpecialistTime;
                $specialisttime->date = $request->date;
                $specialisttime->date_word = $date;
                $specialisttime->time = $times;
                $specialisttime->specialization = $request->specialization;
                $specialisttime->doc_uuid = $request->doc_uuid;
    
                $specialisttime->save();

                // $table->string("date");
                // $table->string("date_word")->nullable();
                // $table->string("time");
                // $table->string("specialization")->nullable();
                // $table->string("doc_uuid");
            }
        }
    
        return back()->with("msg","The selected time has been added successfully");
    }



    //specialist calender time delete

    public function specialistcalendertimedelete(Request $request, $id){
        $delete = SpecialistTime::find($id)->delete();

        return back()->with("msg","Time slot deleted successfully");

       
    }



    //delete specifice specialist schedules and time all


    public function deletespecificspecialistschedules(Request $request, $uuid){
        $times = SpecialistTime::where("doc_uuid",$uuid)->delete();

        $schedule = SpecialistSchedule::where("doc_uuid",$uuid)->delete();

       return back()->with("msg","You have successfully deleted all schedule for this specialist");


    }


    //specialistschedule dates api
   

    public function specialscheduleadatesapi(Request $request){
        $validator = Validator::make($request->all(), [
            'specialization'=> "required",
              
   ]);

            if($validator->fails()){
                return response([
                    'status' => 'failed',
                    'message' => $validator->errors()
                ]);
            }


            $date_list = [];
        $dates = SpecialistSchedule::where("specialization",$request->specialization)->distinct()->get(['date']);

        foreach($dates as $date){

            array_push($date_list,$date["date"]);
        }

        $currentDate = Carbon::today();

// Filter out past dates
$filteredDates = array_filter($date_list, function($date) use ($currentDate) {
    $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
    return $parsedDate->gt($currentDate) || $parsedDate->eq($currentDate);
});
       

       // return $date_list;

        

       // Sort the array using the sortBy() method
        $sortedDates = collect($filteredDates)->sortBy(function ($date) {
            return strtotime(str_replace('/', '-', $date));
        })->values()->all();

        $today =  Carbon::now()->format("d/n/Y");
        
        //return $date_to_remove;

        //return $sortedDates;

        $current_time = Carbon::now()->addHour();
        $seven_pm = Carbon::createFromTime(19, 0, 0);

       // return $current_time;

       
       


    
    $date_to_remove = $today;
    
    if (in_array($date_to_remove, $sortedDates)) {
        $index = array_search($date_to_remove, $sortedDates);
        // unset($sortedDates[$index]);
        // return "The date '$date_to_remove' was removed from the array.";


        if ($current_time->lessThan($seven_pm)) {
        // "The current time is less than 7 pm.";

        return $sortedDates;

        } else {
            // "The current time is 7 pm or later.";
             unset($sortedDates[$index]);

             $dates = array_values($sortedDates);
             return array_slice($dates, 0, 10);
      
        }
    } else {
       

        return  array_slice($sortedDates, 0, 10);
    }
        

    }


    //get specialist schedule time based on date and specialization


    public function specialistscheduletime(Request $request){
        $validator = Validator::make($request->all(), [
            'specialization'=> "required",
            'date'=> 'required',
              
   ]);

            if($validator->fails()){
                return response([
                    'status' => 'failed',
                    'message' => $validator->errors()
                ]);
            }

        $time_list = [];

        
            $times = SpecialistTime::where("date",$request->date)->where("specialization",$request->specialization)->distinct()->get(['time']);

            //return $times;

            foreach($times as $time){
                array_push($time_list, $time["time"]);
            }

            $today =  Carbon::now()->format("d/n/Y");

            if($today == $request->date){
                $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
                $display_time =
                                             array_filter($time_list, function($e) use($now){
                                          
                                  
                                              $hour = date("H:i", strtotime($e));
                                              $delimiter = ':';
                                             $words = explode($delimiter, $hour);
                                             
                                                 $mytimed = $words[0];
                                                 
                                             
                                                     if($mytimed > $now){
                                                         return $e;
                                             
                                             
                                  
                                             
                                          }
                                  
                                  
                                        
                                      });

                                      asort($display_time);

                                     
     
                                      
                                         return array(
                                             'status'=>'success',
                                              'available' => array_slice($display_time, 0, 10),
                                             
                                             );
            }

            else{
                //return $time_list;

                asort($time_list);

                return array(
                    'status'=>'success',
                     'available' => array_slice($time_list, 0, 10),
                    
                    );
}
            }

           
                                  
 
                                  




            //specialist get api, get specialist based on date, specialization and time

            public function specialistgetapi(Request $request){
                $validator = Validator::make($request->all(), [
                    'specialization'=> "required",
                    'date'=> 'required',
                    'time'=> 'required'
                      
           ]);
        
                    if($validator->fails()){
                        return response([
                            'status' => 'failed',
                            'message' => $validator->errors()
                        ]);
                    }


                $specialist = SpecialistTime::where("date",$request->date)->where("specialization",$request->specialization)->where("time",$request->time)->distinct()->get(['doc_uuid']);

                
               // return $specialist;
               $redate  = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
               $retime = Carbon::parse($request->time)->format('H:i:s');

               $all_specialist = [];


               $appointment = Appointment::with(['doctor'])->where("date",$redate)->where("time",$retime)->pluck("doctor_id");

              // return $appointment;



               foreach($specialist as $user){

                



                $doctor = User::where("uuid",$user["doc_uuid"])->first();

                $existed = Appointment::where("date",$redate)->where("time",$retime)->where("doctor_id",$doctor->id)->exists();

                if(!$existed){
                    $all_specialist[] = [
                        "title" => $doctor->title,
                        "firstname" => $doctor->firstname,
                        "aos" => $doctor->aos,
                        "fee" => $doctor->fee,
                        "email" => $doctor->email,
                        "doc_uuid" => $doctor->uuid,
                        "picture" => $doctor->picture,
                        "hospital" => $doctor->hospital
                    ];
                }

                

               

               }

               return response()->json([
                   'status'=>"success",
                   "doctors"=>$all_specialist,
               ]);




            }


            //health center calender get


            public function healthcentercalenderget($id){

                $center = HealthCenter::find($id);
                $spec = Specialization::where("med_center_uuid",$center->uuid)->get();
                return view("healthcentercalender",compact("center","spec"));
            }


            //healthcenter spec manage calender

            public function healthcenterspecalender($id){
                $specialization = Specialization::find($id);

                $calender = MedcenterSchedule::where("center_uuid",$specialization->med_center_uuid)->where("specialization",$specialization->specialization)->get();

                //dd($specialization);

                return view("healthspecalender",compact("specialization","calender"));
            }




            //healthcenter spec manage calender post


            public function healthcenterspecpost(Request $request){

                         
        $month = date('m'); //uncomment this tomorrow 
        $monthstring = date("F", strtotime('m')); 
        
    
       
         $date = $request->mydates;
 
         $selected = explode(",", $date);
 
 
        foreach($selected as $select){
         $existed = MedcenterSchedule::where("center_uuid",$request->center_uuid)->where('date',$select)->where('specialization',$request->specialization)->exists();
 
         if($existed){
             return back()->with('error','Date(s) already exists in the calendar for ' .$request->specialization);
         } 
 
     
            $calender = new MedcenterSchedule;
 
            $calender->center_uuid = $request->center_uuid;
            $calender->specialization = $request->specialization;
           
            $calender->date = $select;
            $calender->month = $month;
            $calender->monthstring = $monthstring;
 
            $calender->save();

            // $table->string('center_uuid');
            // $table->string("specialization");
          
            // $table->string("date");
            // $table->integer("fee")->nullable();
            // $table->string("month");
            // $table->string("monthstring");
 
            
        }
 
        return back()->with("msg",'Calandar created successfully');

            }


            //delete healthcenter spec dates unique

            public function deleteuniquehealthcenterspecdate(Request $request, $id){
                $delete = MedcenterSchedule::find($id);

                $time = MedcenterTime::where("center_uuid",$delete->center_uuid)->where("date",$delete->date)->where("specialization",$delete->specialization)->delete();
                $delete = MedcenterSchedule::find($id)->delete();

               return back()->with("msg","Date removed from calender successfully");
            }



            ///health center get time for each available date
            

            public function healthcenterspecdatetime($id){

                $schedule = MedcenterSchedule::find($id);
                $available = MedcenterTime::where("center_uuid",$schedule->center_uuid)->where("date",$schedule->date)->where("specialization",$schedule->specialization)->get();

                return view("healthcenterdatetime",compact("schedule","available"));
            }



            //healthcenter specdate time post 

            public function healthcenterspecdatetimepost(Request $request){

                if(!$request->time){
                    return back()->with("error","Kindly select at least a time");
                }
               
                $date = Carbon::createFromFormat('d/m/Y',$request->date)->format('l, F d, Y');
                 //dd($date);
                foreach($request->time as $times){
                    //dump($times);
            
                    $existed = MedcenterTime::where("date",$request->date)->where("time",$times)->where("center_uuid",$request->center_uuid)->where("specialization",$request->specialization)->exists();
            
                    if(!$existed){
                        
                        $healtcentertime = new MedcenterTime;
                        $healtcentertime->date = $request->date;
                        $healtcentertime->date_word = $date;
                        $healtcentertime->time = $times;
                        $healtcentertime->specialization = $request->specialization;
                        $healtcentertime->center_uuid = $request->center_uuid;
            
                        $healtcentertime->save();
        
                        // $table->string("date");
                        // $table->string("date_word")->nullable();
                        // $table->string("time");
                        // $table->string("specialization")->nullable();
                        // $table->string("doc_uuid");
                    }
                }
            
                return back()->with("msg","The selected time has been added successfully");

            }


            //health center spec date time delete

            public function healthcenterspecdatetimedelete(Request $request, $id){
                $delete = MedcenterTime::find($id)->delete();

                
                return back()->with("msg","You have successfully deleted a time from this schedule");

            }



            //health delete all calendar date and time


            public function healthdeleteallcalenderdateandtime(Request $request, $id){

                $specialization = Specialization::find($id);

                //dd($specialization);


                $time = MedcenterTime::where("center_uuid",$specialization->med_center_uuid)->where("specialization",$specialization->specialization)->delete();

                $schedule = MedcenterSchedule::where("center_uuid",$specialization->med_center_uuid)->where("specialization",$specialization->specialization)->delete();

                return back()->with("msg","Calender schedule has been clear successfully");


            }



            //health center facility get spec dates api


            public function healthcenterdatesapi(Request $request){

                $validator = Validator::make($request->all(), [
                    'specialization'=> "required",
                    "center_uuid" => "required"
                      
           ]);
        
                    if($validator->fails()){
                        return response([
                            'status' => 'failed',
                            'message' => $validator->errors()
                        ]);
                    }

                    ////////////////////

                    $date_list = [];
                    $dates = MedcenterSchedule::where("specialization",$request->specialization)->where("center_uuid",$request->center_uuid)->distinct()->get(['date']);

              
            
                    foreach($dates as $date){
            
                        array_push($date_list,$date["date"]);
                    }
            
                    $currentDate = Carbon::today();
            
            // Filter out past dates
            $filteredDates = array_filter($date_list, function($date) use ($currentDate) {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
                return $parsedDate->gt($currentDate) || $parsedDate->eq($currentDate);
            });
                   
            
                   // return $date_list;
            
                    
            
                   // Sort the array using the sortBy() method
                    $sortedDates = collect($filteredDates)->sortBy(function ($date) {
                        return strtotime(str_replace('/', '-', $date));
                    })->values()->all();
            
                    $today =  Carbon::now()->format("d/n/Y");
                    
                    //return $date_to_remove;
            
                    //return $sortedDates;
            
                    $current_time = Carbon::now()->addHour();
                    $seven_pm = Carbon::createFromTime(19, 0, 0);
            
                   // return $current_time;
            
                   
                   
            
            
                
                $date_to_remove = $today;
                
                if (in_array($date_to_remove, $sortedDates)) {
                    $index = array_search($date_to_remove, $sortedDates);
                    // unset($sortedDates[$index]);
                    // return "The date '$date_to_remove' was removed from the array.";
            
            
                    if ($current_time->lessThan($seven_pm)) {
                    // "The current time is less than 7 pm.";
            
                    return $sortedDates;
            
                    } else {
                        // "The current time is 7 pm or later.";
                         unset($sortedDates[$index]);
            
                         $dates = array_values($sortedDates);
                         return array_slice($dates, 0, 10);
                  
                    }
                } else {
                   
            
                    return  array_slice($sortedDates, 0, 10);
                }



                    ///////////////////

            }


            //healthcenter appointment spec time api
            
            
            public function healthcenterdatetimeapi(Request $request){

                $validator = Validator::make($request->all(), [
                    'specialization'=> "required",
                    'date'=> 'required',
                    "center_uuid" => 'required'
                      
           ]);
        
                    if($validator->fails()){
                        return response([
                            'status' => 'failed',
                            'message' => $validator->errors()
                        ]);
                    }

                

                    /////////////////////////

                    $time_list = [];

        
            $times = MedcenterTime::where("center_uuid",$request->center_uuid)->where("date",$request->date)->where("specialization",$request->specialization)->distinct()->get(['time']);


            foreach($times as $time){
                array_push($time_list, $time["time"]);
            }

            $today =  Carbon::now()->format("d/n/Y");

            if($today == $request->date){
                $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
                $display_time =
                                             array_filter($time_list, function($e) use($now){
                                          
                                  
                                              $hour = date("H:i", strtotime($e));
                                              $delimiter = ':';
                                             $words = explode($delimiter, $hour);
                                             
                                                 $mytimed = $words[0];
                                                 
                                             
                                                     if($mytimed > $now){
                                                         return $e;
                                             
                                             
                                  
                                             
                                          }
                                  
                                  
                                        
                                      });

                                      asort($display_time);

                                     
     
                                      
                                         return array(
                                             'status'=>'success',
                                              'available' => array_slice($display_time, 0, 10),
                                             
                                             );
            }

            else{
                //return $time_list;

                asort($time_list);

                return array(
                    'status'=>'success',
                     'available' => array_slice($time_list, 0, 10),
                    
                    );
}






                    ///////////////////////////


                    


        

            }





            //nellowbsite get dates for doctors

          


            public function getwebsitedoctordate(Request $request){
                $validator = Validator::make($request->all(), [
                    'uuid'=> "required",
                      
           ]);
        
                    if($validator->fails()){
                        return response([
                            'status' => 'failed',
                            'message' => $validator->errors()
                        ]);
                    }
        
        
                    $date_list = [];
                $dates = SpecialistSchedule::where("doc_uuid",$request->uuid)->distinct()->get(['date']);
        
                foreach($dates as $date){
        
                    array_push($date_list,$date["date"]);
                }
        
                $currentDate = Carbon::today();
        
        // Filter out past dates
        $filteredDates = array_filter($date_list, function($date) use ($currentDate) {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
            return $parsedDate->gt($currentDate) || $parsedDate->eq($currentDate);
        });
               
        
               // return $date_list;
        
                
        
               // Sort the array using the sortBy() method
                $sortedDates = collect($filteredDates)->sortBy(function ($date) {
                    return strtotime(str_replace('/', '-', $date));
                })->values()->all();
        
                $today =  Carbon::now()->format("d/n/Y");
                
                //return $date_to_remove;
        
                //return $sortedDates;
        
                $current_time = Carbon::now()->addHour();
                $seven_pm = Carbon::createFromTime(19, 0, 0);
        
               // return $current_time;
        
               
               
        
        
            
            $date_to_remove = $today;
            
            if (in_array($date_to_remove, $sortedDates)) {
                $index = array_search($date_to_remove, $sortedDates);
                // unset($sortedDates[$index]);
                // return "The date '$date_to_remove' was removed from the array.";
        
        
                if ($current_time->lessThan($seven_pm)) {
                // "The current time is less than 7 pm.";
        
                $newDates = array_map(function($date) {
                    return ["dates" => explode('/', $date)[0]];
                }, $sortedDates);

                return $newDates;
                
        
                } else {
                    // "The current time is 7 pm or later.";
                     unset($sortedDates[$index]);
        
                     $dates = array_values($sortedDates);

                     $newDates = array_map(function($date) {
                        return ["dates" => explode('/', $date)[0]];
                    }, $dates);
    
                    return $newDates;
              
                }
            } else {
               
        
                $newDates = array_map(function($date) {
                    return ["dates" => explode('/', $date)[0]];
                }, $sortedDates);

                return $newDates;



            }
            }


            //nello website appointment time

        public function getwebsitedoctortime(Request $request){
            $validator = Validator::make($request->all(), [
                'uuid'=> "required",
                'date'=> 'required',
                  
       ]);
    
                if($validator->fails()){
                    return response([
                        'status' => 'failed',
                        'message' => $validator->errors()
                    ]);
                }

                $date = date_create_from_format('l, F j, Y', $request->date);
                $formattedDate = date_format($date, 'j/n/Y');
                $yearformat = Carbon::createFromFormat('d/m/Y',$formattedDate)->format('Y-m-d');
                
              
                //return $formattedDate;

    
            $time_list = [];
    
            
                $times = SpecialistTime::where("date",$formattedDate)->where("doc_uuid",$request->uuid)->distinct()->get(['time']);
                $doctor_id = User::where("uuid",$request->uuid)->value("id");
              
    
                //return $times;
    
                foreach($times as $time){

                    $timehour = Carbon::parse($time["time"])->format('h:ia');

                    $existed = Appointment::where("doctor_id",$doctor_id)->where("date",$yearformat)->where("time",$timehour)->exists();

                    if(!$existed){
                        array_push($time_list, $time["time"]);
                    }
                    
                }
    
                $today =  Carbon::now()->format("d/n/Y");
    
                if($today == $formattedDate){
                    $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
                    $display_time =
                                                 array_filter($time_list, function($e) use($now){
                                              
                                      
                                                  $hour = date("H:i", strtotime($e));
                                                  $delimiter = ':';
                                                 $words = explode($delimiter, $hour);
                                                 
                                                     $mytimed = $words[0];
                                                     
                                                 
                                                         if($mytimed > $now){
                                                             return $e;
                                                 
                                                 
                                      
                                                 
                                              }
                                      
                                      
                                            
                                          });
    
                                          asort($display_time);

                                          $mytime =[];
    
                                         
         
                                          
                                             //return $display_time;
                                             foreach($display_time as $timess){
                                                 array_push($mytime,$timess);
                                             }

                                             return $mytime;
                }
    
                else{
                    $mytime =[];
                    //return $time_list;
    
                    asort($time_list);
    
                    foreach($time_list as $timess){
                        array_push($mytime,$timess);
                    }

                    return $mytime;
    }

        }


        //gett nelloo website facility date

        public function getwebsitefacilitydate(Request $request){

            
            $validator = Validator::make($request->all(), [
                'specialization'=> "required",
                "center_uuid" => "required"
                  
       ]);
    
                if($validator->fails()){
                    return response([
                        'status' => 'failed',
                        'message' => $validator->errors()
                    ]);
                }

                ////////////////////

                $date_list = [];
                $dates = MedcenterSchedule::where("specialization",$request->specialization)->where("center_uuid",$request->center_uuid)->distinct()->get(['date']);

          
        
                foreach($dates as $date){
        
                    array_push($date_list,$date["date"]);
                }
        
                $currentDate = Carbon::today();
        
        // Filter out past dates
        $filteredDates = array_filter($date_list, function($date) use ($currentDate) {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
            return $parsedDate->gt($currentDate) || $parsedDate->eq($currentDate);
        });
               
        
               // return $date_list;
        
                
        
               // Sort the array using the sortBy() method
                $sortedDates = collect($filteredDates)->sortBy(function ($date) {
                    return strtotime(str_replace('/', '-', $date));
                })->values()->all();
        
                $today =  Carbon::now()->format("d/n/Y");
                
                //return $date_to_remove;
        
                //return $sortedDates;
        
                $current_time = Carbon::now()->addHour();
                $seven_pm = Carbon::createFromTime(19, 0, 0);
        
               // return $current_time;
        
               
               
        
        
            
            $date_to_remove = $today;
            
            if (in_array($date_to_remove, $sortedDates)) {
                $index = array_search($date_to_remove, $sortedDates);
                // unset($sortedDates[$index]);
                // return "The date '$date_to_remove' was removed from the array.";
        
        
                if ($current_time->lessThan($seven_pm)) {
                // "The current time is less than 7 pm.";
        
                $newDates = array_map(function($date) {
                    return ["dates" => explode('/', $date)[0]];
                }, $sortedDates);

                return $newDates;
        
                } else {
                    // "The current time is 7 pm or later.";
                     unset($sortedDates[$index]);
        
                     $dates = array_values($sortedDates);
                     
                     $newDates = array_map(function($date) {
                        return ["dates" => explode('/', $date)[0]];
                    }, $dates);
    
                    return $newDates;
              
                }
            } else {
               
        
                $newDates = array_map(function($date) {
                    return ["dates" => explode('/', $date)[0]];
                }, $sortedDates);

                return $newDates;
            }


        }


        public function getnellowebsitefacilitytime(Request $request){

            
            $validator = Validator::make($request->all(), [
                'uuid'=> "required",
                'date'=> 'required',
                'specialization' => 'required'
                  
       ]);
    
                if($validator->fails()){
                    return response([
                        'status' => 'failed',
                        'message' => $validator->errors()
                    ]);
                }

                $date = date_create_from_format('l, F j, Y', $request->date);
                $formattedDate = date_format($date, 'j/n/Y');
                $yearformat = Carbon::createFromFormat('d/m/Y',$formattedDate)->format('Y-m-d');
                
              
                //return $formattedDate;

    
            $time_list = [];
    
            
                $times = MedcenterTime::where("date",$formattedDate)->where("center_uuid",$request->uuid)->where("specialization",$request->specialization)->distinct()->get(['time']);
              
              
    
                //return $times;
    
                foreach($times as $time){

                    $timehour = Carbon::parse($time["time"])->format('h:ia');

                    $existed = Appointment::where("center_uuid",$request->uuid)->where("date",$yearformat)->where("time",$timehour)->exists();

                    if(!$existed){
                        array_push($time_list, $time["time"]);
                    }
                    
                }
    
                $today =  Carbon::now()->format("d/n/Y");
    
                if($today == $formattedDate){
                    $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
                    $display_time =
                                                 array_filter($time_list, function($e) use($now){
                                              
                                      
                                                  $hour = date("H:i", strtotime($e));
                                                  $delimiter = ':';
                                                 $words = explode($delimiter, $hour);
                                                 
                                                     $mytimed = $words[0];
                                                     
                                                 
                                                         if($mytimed > $now){
                                                             return $e;
                                                 
                                                 
                                      
                                                 
                                              }
                                      
                                      
                                            
                                          });
    
                                          asort($display_time);

                                          $mytime =[];
    
                                         
         
                                          
                                             //return $display_time;
                                             foreach($display_time as $timess){
                                                 array_push($mytime,$timess);
                                             }

                                             return $mytime;
                }
    
                else{
                    $mytime =[];
                    //return $time_list;
    
                    asort($time_list);
    
                    foreach($time_list as $timess){
                        array_push($mytime,$timess);
                    }

                    return $mytime;
    }

        }



        //appointment switch 

        public function switch($id){

            $appointment = Appointment::find($id);
            $specialist = User::where("aos",$appointment->doctor->aos)->where("id","!=",$appointment->doctor_id)->where("active",true)->get();

            

            return view("appointmentswitch",compact("appointment","specialist"));

           

            
        }


        //switch appointment put

        public function switchspecialist(Request $request){

            $newdoctor = User::where("email",$request->email)->first();

            

            $new_doctor_title = $newdoctor->title;
            $new_doctor_firstname = $newdoctor->firstname;

            $date = Appointment::where("ref_no",$request->ref_no)->value("date");
            $time = Appointment::where("ref_no",$request->ref_no)->value("time");

            $appointment =  Appointment::where("ref_no",$request->ref_no)->first();
            $username = $appointment->user->firstname;

            $old_doctor_title = $appointment->doctor->title;
            $old_doctor_firstname = $appointment->doctor->firstname;
            $specialization = $appointment->doctor->aos;

            $link = $appointment->link;

            $useremail = $appointment->user->email;

            $new_doctor_email = $newdoctor->email;




           $appointment = Appointment::where("ref_no",$request->ref_no)->update([
               'doctor_id' => $newdoctor->id
           ]);

           $resbooking = Http::withoutVerifying()->post('https://mw.asknello.com/api/appointmentswitchemails',[
                
            'username' => $username,

       
            'user_email' => $useremail,
            'date' => $date,
            'time' => $time,
            'old_doctor_title' => $old_doctor_title,
            'old_doctor_firstname' => $old_doctor_firstname,
            'new_doctor_title' => $new_doctor_title,
            'new_doctor_firstname' => $new_doctor_firstname,
            'new_doctor_email' => $new_doctor_email,
            'specialization' => $specialization,
            'link' => $link,
            
        ]);

        if($resbooking['status'] == 'success'){
            return back()->with("msg","Appointment has been switched successfully");
        }

           

          
        }


        //nello medical report get appointment ref

        public function getappointmentref($ref){
           $appointment = Appointment::with("user")->where('ref_no',$ref)->first();

           return $appointment;
        }



        //nello medical report post create new record


        public function createnewrecord(Request $request){

            // $table->string("ref");
            // $table->text("symptoms");
            // $table->string("other_symptoms")->nullable();
            // $table->text("histor_of_compliants");
            // $table->string("allergies");
            // $table->text("diagnosis");
            // $table->string("other_diagnosis")->nullable();
            // $table->text("procedures")->nullable();
            // $table->text("comments");
            // $table->text("prescriptions")->nullable();
            // $table->string("followup_date")->nullable();
            // $table->string("followup_time")->nullable();
            // $table->string("outcome")->nullable("");

            // const[symptomsList , setSymptomsList] = useState([]);
            // const[refno, setRefNo] = useState("");
        
            // const[othersymptoms , setOtherSymptoms] = useState("");
            // const[historyOfCompliants , setHistoryOfCompliants] = useState("");
        
            // const[allergies , setAllergies] = useState([]);
            // const[diagnosesList , setDiagnosesList]= useState([]);
            // const[otherDiagnosis , setOtherDiagnosis] = useState("");
            // const[procedureList , setProcedureList] = useState([]);
            // const[comments , setComment] = useState('');
            // const[prescriptions , setPrescriptions] = useState([]);
            // const[followUpDate , setFollowUpDate] = useState("");
            // const[followUpTime , setFollowUpTime] = useState("");


            $report = new MedicalReport;

            $report->ref = $request->refno;
            $report->symptoms = json_encode($request->symptomsList);
            $report->other_symptoms = $request->other_symptoms;
            $report->histor_of_compliants = $request->historyOfCompliants;
            $report->allergies = json_encode($request->allergies);
            $report->diagnosis = json_encode($request->diagnosesList);
            $report->other_diagnosis = $request->otherDiagnosis;
            $report->procedures = json_encode($request->procedureList);
            $report->comments = $request->comments;
            $report->prescriptions = json_encode($request->prescriptions);
            $report->followup_date = $request->followUpDate;
            $report->followup_time  = $request->followUpTime;

            $report->save();


            return response()->json("Consultation Records has been submitted succesfully");

            
           
        }


        //get all medical records 


        public function getallmedicalrecords(){
            $records = MedicalReport::all();

            return $records;
        }


        //ask nello getsearched input for caretype and specialization



        public function getsearchinput(){
            

            $mysearch = [];
            $specialization= User::where("user_type","doctor")->where("active",true)->distinct()->get(['aos']);
            $caretype= Specialization::distinct()->get(['specialization']);

            foreach($specialization as $spec){
                array_push($mysearch,$spec["aos"]);
            }


            foreach($caretype as $care){
                array_push($mysearch,$care['specialization']);
            }



           return $mysearch;


        }

        //nello frontend get doctor center


        public function getdoctorcenter(Request $request){


            if($request->locationvalue){
                $mydoctors = [];

                if($request->service != ''){
                $doctor = User::where("active",true)->where('aos', 'LIKE', '%'.$request->service.'%')->get();
                
                //return $doctor;
    
                $med_centers = [];
                
                foreach($doctor as $doc){
                    $med_centers[]=[
                        "firstname" => $doc->firstname,
                        "lastname" => $doc->lastname,
                        "hospital"=>$doc->hospital,
                        "aos"=>$doc->aos,
                        "uuid" => $doc->uuid,
                        "picture" => $doc->picture,
                        "type" => "doctor"
                    ];
                }
            }
    
                $centers = Specialization::where("specialization",$request->service)->distinct()->get(['med_center_uuid']);
    
         
    
              
                // return $centers;
                foreach($centers as $med){
                    $healthcenter = HealthCenter::where("is_active",true)->where("uuid",$med->med_center_uuid)->first();
                    
                    $med_centers[] = [
                        "name" => $healthcenter->name,
                        "center_type" => $healthcenter->center_type,
                        "city" => $healthcenter->city,
                        "uuid" => $healthcenter->uuid,
                        "logo" => $healthcenter->logo,
                        "type" => "center",
                    ];
    
                }
    
                $centerByLocation = HealthCenter::where("is_active",true)->where('address1', 'LIKE', '%'.$request->locationvalue.'%')
                ->orWhere('city', $request->locationvalue)
                ->get();
    
    
                foreach($centerByLocation as $med){
                    $healthcenter = HealthCenter::where("uuid",$med->uuid)->first();
                    
                    $med_centers[] = [
                        "name" => $healthcenter->name,
                        "center_type" => $healthcenter->center_type,
                        "city" => $healthcenter->city,
                        "uuid" => $healthcenter->uuid,
                        "logo" => $healthcenter->logo,
                        "type" => "center",
                    ];
    
                }
    
               // $uniqueArray = array_unique($med_centers);
            
               $collection = collect($med_centers);
    
               // Remove duplicates based on the "uuid" key
               $uniqueCenters = $collection->unique('uuid')->values()->all();
    
                return $uniqueCenters;


            }

            else{

                $mydoctors = [];

                if($request->service != ''){
                $doctor = User::where("active",true)->where('aos', 'LIKE', '%'.$request->service.'%')->get();
    
                //return $doctor;
    
                $med_centers = [];
                
                foreach($doctor as $doc){
                    $med_centers[]=[
                        "firstname" => $doc->firstname,
                        "lastname" => $doc->lastname,
                        "hospital"=>$doc->hospital,
                        "aos"=>$doc->aos,
                        "uuid" => $doc->uuid,
                        "picture" => $doc->picture,
                        "type" => "doctor"
                    ];
                }
            }
                $centers = Specialization::where("specialization",$request->service)->distinct()->get(['med_center_uuid']);
    
         
    
              
                // return $centers;
                foreach($centers as $med){
                    $healthcenter = HealthCenter::where("is_active",true)->where("uuid",$med->med_center_uuid)->first();
                    
                    $med_centers[] = [
                        "name" => $healthcenter->name,
                        "center_type" => $healthcenter->center_type,
                        "city" => $healthcenter->city,
                        "uuid" => $healthcenter->uuid,
                        "logo" => $healthcenter->logo,
                        "type" => "center",
                    ];
    
                }
    
                
    
    
               
    
               // $uniqueArray = array_unique($med_centers);
            
               $collection = collect($med_centers);
    
               // Remove duplicates based on the "uuid" key
               $uniqueCenters = $collection->unique('uuid')->values()->all();
    
                return $uniqueCenters;

            }

        
        }

    }





