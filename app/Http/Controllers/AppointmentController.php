<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\HealthCenter;
use App\SpecialistSchedule;
use App\SpecialistTime;
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
       
    //    $monthstring = date("F", strtotime('first day of +1 month')); //comment this tomorrow
    //    $month = date('m',strtotime('first day of +1 month')); //comment this tomorrow
       
      
        $date = $request->mydates;

        $selected = explode(",", $date);


       foreach($selected as $select){
        $existed = SpecialistSchedule::where("doc_uuid",$request->doc_uuid)->where('date',$select)->where('specialization',$request->specialization)->exists();

        if($existed){
            return back()->with('error','Date(s) already exists in the calendar for ' .$request->specialization);
        } 

        //uncomment the above later

        // $table->string('doc_uuid');
        //     $table->string("specialization");
        //     $table->string("center");
        //     $table->string("date");
        //     $table->integer("fee")->nullable();
        //     $table->string("month");
        //     $table->string("monthstring");

           $calender = new SpecialistSchedule;

           $calender->doc_uuid = $request->doc_uuid;
           $calender->specialization = $request->specialization;
           $calender->center = $request->center;
           $calender->date = $select;
           $calender->month = $month;
           $calender->monthstring = $monthstring;

           $calender->save();

        

        // $owc->specialization = $request->specialization;
        // $owc->center = "OWC";
        // $owc->date = $select;
        // $owc->month=$month;
        // $owc->monthstring = $monthstring;

        // $owc->save();
           
       }

       return back()->with("msg",'Calandar created successfully');
    //dd($month);
   // dump($monthstring);
        

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

       // return $date_list;

        

       // Sort the array using the sortBy() method
        $sortedDates = collect($date_list)->sortBy(function ($date) {
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

               $all_specialist = [];

               foreach($specialist as $user){


                $doctor = User::where("uuid",$user["doc_uuid"])->first();

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

               return response()->json([
                   'status'=>"success",
                   "doctors"=>$all_specialist,
               ]);




            }



    }





