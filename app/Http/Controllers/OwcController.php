<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Appointment;
use App\DocSchedule;
use App\TempAppointment;
use Illuminate\Support\Str;
use DB;
use App\MedSchedule;
use App\Models\HealthCenter;
use Spatie\WebhookServer\WebhookCall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use App\ChatToken;
use App\Owcappointment;
use PDF;
use App\Owcalendar;
use DateTime;
use App\Owctime;


class OwcController extends Controller
{
    //create an appointment


    public function create(Request $request){
        $validator = Validator::make($request->all(), [
       
       
            'date'=> 'required',
            'time' => 'required',
            'caretype' => 'required',
            'user_firstname' =>'required',
            'user_lastname' => 'required',
            'email' =>'required',
            'title' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            
]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }


        ///
       $max = 900000;
       $min = 100000;
       $rand = rand($min, $max);

       $ref = (string)$rand;


       if(!$request->type){

        //if no request type

       

        $appointment = new OWCappointment;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->caretype = $request->caretype;
        $appointment->user_firstname = $request->user_firstname;
        $appointment->user_lastname  = $request->user_lastname;
        $appointment->email  = $request->email;
        $appointment->title = $request->title;
        $appointment->dob = $request->dob;
        $appointment->phone = $request->phone;
        $appointment->gender = $request->gender;
        $appointment->ref = $ref;

        
        $appointment->save();

        $data = $appointment->fresh();


        $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/owcmail',[
           "email" => $request->email,
           "date" => $request->date,
           "title" => $request->title,
           "time" => $request->time,
           "ref" => $ref,
           "caretype" => $request->caretype,
           "user_firstname" => $request->user_firstname
        ]);


        return response()->json([
            'status'=> "success",
            "data" => $data
        ]);

    }

    elseif($request->type){
        $appointment = new OWCappointment;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->caretype = $request->caretype;
        $appointment->user_firstname = $request->user_firstname;
        $appointment->user_lastname  = $request->user_lastname;
        $appointment->email  = $request->email;
        $appointment->title = $request->title;
        $appointment->dob = $request->dob;
        $appointment->phone = $request->phone;
        $appointment->gender = $request->gender;
        $appointment->ref = $ref;



        $appointment->type = $request->type;
        $appointment->doctor = $request->doctor;
        $appointment->link = $request->link;
        $appointment->payment_ref = $request->payment_ref;

        
        $appointment->save();

        $data = $appointment->fresh();

        

        $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/owcmail',[
           "email" => $request->email,
           "date" => $request->date,
           "title" => $request->title,
           "time" => $request->time,
           "ref" => $ref,
           "caretype" => $request->caretype,
           "user_firstname" => $request->user_firstname,
           "type" => $request->type,
           "doctor" => $request->doctor,
           "link" => $request->link,
           "doctor_email" => $request->doctor_email,
        ]);


        return response()->json([
            'status'=> "success",
            "data" => $data
        ]);


    }

    }


    //delete owcappointment 

    public function deleteappointment(Request $request,$id){
        $appointment = Owcappointment::find($id)->delete();

        return back()->with("msg","Appointment deleted successfully");
    }


    // go to appointment page

    public function getappointment(Request $request){
        $search = $request->search;
        $type = $request->type;

        $size = empty($request->size) ? 10 : $request->size;

        $appointments = Owcappointment::where( function ($query) use ($search) {

                $query->when($search, function ($query, $search) {

                    $query->whereRaw(
                        "(user_firstname like ? or user_lastname like ? or phone like ? or email like ?)",
                        [
                            "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                        ]
                    );
                });

                // })->whereHas('center', function ($query) use ($search) {

                //     $query->when($search, function ($query, $search) {

                //         $query->whereRaw(
                //             "(name like ? or state like ? or phone like ? or email like ?)",
                //             [
                //                 "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                //             ]
                //         );

                //     });

            })
            ->when($type, function ($query, $type) {

                $query->whereRaw(
                    "(caretype like ? )",
                    [
                        "%{$type}%"
                    ]
                );
            })
            ->orderBy('id')
            
            ->paginate($size);

            

        return view('owcappointment', compact('appointments', 'search', 'size','type'));
    }

    //check appointment availaibilty

    public function checkappointment(Request $request){

        $validator = Validator::make($request->all(), [
       
       
            'date'=> 'required',
            'time' => 'required',
            'caretype' => 'required',
            
            
]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }
        $appointment = Owcappointment::where('date',$request->date)->where('time',$request->time)->where('caretype',$request->caretype)->exists();


        if($appointment){
            return response()->json([
                'status' => "booked",
                "message" => 'Selected Date and Time has already been booked  a '.$request->caretype
            ] );
        }
        
        else{

            return response()->json([
                'status' => "available",
                "message" => 'Selected Date and Time is available for a '.$request->caretype
            ] );
        }
    }

    public function download($ref){
        $appointment = Owcappointment::where('ref',$ref)->first();
    
        $pdf = PDF::loadView('owcdownload',compact('appointment'));
        return $pdf->download('Confirmation_Slip.pdf');
    }


    //check time 

    public function checktime(Request $request){
        $validator = Validator::make($request->all(), [
       
       
            'date'=> 'required',
           
            'caretype' => 'required',
            
            
]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }

        $appointment = Owcappointment::where('date',$request->date)->where('caretype',$request->caretype)->exists();



        if($appointment){
            $appointment = Owcappointment::where('date',$request->date)->where('caretype',$request->caretype)->distinct()->get(['time']);
            return response()->json([
                'status' =>"booked",
                "time" => $appointment

            ]);
        }

        else {
            return response()->json([
                'status' =>"notbooked",
                

            ]);
        }
    }


    //dashboard

    public function dashboard(){
        return view('owcdashboard');
    }

    ///owc calendar

    public function medicalcalendar(){
        $owc = Owcalendar::all();

        $general = Owcalendar::where("specialization","General Practitioner")->get();
        $gynecology = Owcalendar::where("specialization","Gynaecologist")->get();
        $aesthesian = Owcalendar::where("specialization","Aesthetician")->get();
        $urologist = Owcalendar::where("specialization","Urologist")->get();
        return view('owcmedschedule',compact('general', 'gynecology', 'aesthesian','urologist'));
    }


    //OWC Calendar Post

    public function medicalcalendarpost(Request $request){


        //month number
        $timestamp = strtotime(str_replace('/', '-', "28/2/2023"));
        $monthNumber = date('m', $timestamp);


        // month string
        $timestamp = strtotime(str_replace('/', '-', "28/2/2023"));
        $monthName = date('F', $timestamp);


        
        
    //     $month = date('m'); //uncomment this tomorrow 
    //   $monthstring = date("F", strtotime('m'));  

     
       
 
      
        $date = $request->mydates;

        $selected = explode(",", $date);


       foreach($selected as $select){
        $existed = Owcalendar::where('date',$select)->where('specialization',$request->specialization)->exists();


        if($existed){
            return back()->with('error','Date(s) already exists in the calendar for ' .$request->specialization);
        } 

        //uncomment the above later

        $timestamp = strtotime(str_replace('/', '-', $select));
        $monthNumber = date('m', $timestamp);


        // month string
        $timestamp = strtotime(str_replace('/', '-', $select));
        $monthName = date('F', $timestamp);



           $owc = new Owcalendar;

       

        $owc->specialization = $request->specialization;
        $owc->center = "OWC";
        $owc->date = $select;
        $owc->month=$monthNumber;
        $owc->monthstring = $monthName;

        $owc->save();
           
       }

       return back()->with("msg",'Calandar created successfully');
    
    }

    //delete unique calendar date

    public function deletecalendardate($id){
        $calender = Owcalendar::where("center",'OWC');

        if($calender !=null){
            $calender = Owcalendar::find($id)->delete();

            return back()->with('msg','Date deleted from calendar successfully');
        }
       
    }


    //delete entire calendar created

    public function calendardelete(Request $request){
        $calenders = Owcalendar::where("center",'OWC')->get();

        

        Owcalendar::truncate();

        return back()->with('msg',' calendar deleted successfully');
        
    }


    //get date array owc

  public function getdatearray(Request $request){
      //

    $calendar = Owcalendar::where("specialization",$request->specialization)->get();

      //return $calendar;

      $dateArray = [];

      foreach($calendar as $date){
        $mydate = $date->date;

        $selected = explode("/", $mydate);
            $dateArray[]=[
                "dates"=> $selected[0],
            ];
      }

      return $dateArray;
}


public function getowcdatechatbot(Request $request){

    $date  = intval(date('d'));

    

    

    $calendar = Owcalendar::where("specialization",$request->specialization)->get();

      //return $calendar;

      $dateArray = [];

      foreach($calendar as $date){
        $mydate = $date->date;

        $result = Carbon::createFromFormat('d/m/Y', $mydate)->isPast();

        $today = date('d/m/Y');

        if(!$result || $today == $mydate){
            $selected = explode("/", $mydate);

        //$selected_int = intval($selected[0])
            $dateArray[]=[
               
                "date" => $mydate
            ];
        }

        
      }

      return  array_slice($dateArray, 0, 10);
}


//check time bot OWC 


public function checktimebot(Request $request){
    $validator = Validator::make($request->all(), [
       
       
        'date'=> 'required',
       
        'caretype' => 'required',
        
        
]);


    if($validator->fails()){
    return response([
        'status' => 'failed',
        'message' => $validator->errors()
    ]);
    }

    $today = date("d/m/Y");

//     date_default_timezone_set('Africa/Lagos');

//     // Convert the date
//     $date = $request->date;

//   $date =   date('d/m/Y', strtotime($date));
//     $date = date('l, F d, Y', strtotime($date));

    $date = Carbon::createFromFormat('d/m/Y', $request->date);
$date = $date->format('l, F d, Y');


    $timeArray = [
       
        "10:00 am",
        "10:30 am",
        "11:00 am",
        "11:30 am",
        "12:00 pm",
        "12:30 pm",
        "2:00 pm",
        "2:30 pm",
        "3:00 pm",
        "3:30 pm",
        "4:00 pm",
        "4:30 pm",
        "5:00 pm",
    ];

   

    

    $appointment = Owcappointment::where('date',$date)->where('caretype',$request->caretype)->exists();



    if($appointment){

        
        $appointment = Owcappointment::where('date',$date)->where('caretype',$request->caretype)->distinct()->get(['time']);
        
        $bookedtime = $appointment;


        
      

        $bookArray = [];

        foreach($bookedtime as $book){
            array_push($bookArray, $book["time"]);
        }

        

       
        

        $mytime = [];
        // Use array_diff() to compare the $timeArray and $response arrays and get the values from $timeArray that are not present in $response
        $nonSimilarValues = array_diff($timeArray, $bookArray);

        // Output the values from $nonSimilarValues
        foreach ($nonSimilarValues as $time) {
            array_push($mytime , $time);
        }

        

        

        ////////

        if($today == $request->date){
            // filter pasted time

            $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
           $display_time =
                                        array_filter($mytime, function($e) use($now){
                                     
                             
                                         $hour = date("H:i", strtotime($e));
                                         $delimiter = ':';
                                        $words = explode($delimiter, $hour);
                                        
                                            $mytimed = $words[0];
                                            
                                        
                                                if($mytimed > $now){
                                                    return $e;
                                        
                                        
                             
                                        
                                     }
                             
                             
                                   
                                 });

                                 
                                    return array(
                                        'status'=>'success',
                                         'available' => array_slice($display_time, 0, 10),
                                        
                                        );
                                 

                                 


       
        }

        else{
            //return array_slice($mytime, 0, 10);

            return array(
                'status'=>'success',
                 'available' => array_slice($mytime, 0, 10),
                
                );
           

                                 


           ////
           
        }
    }

    else {
        // return response()->json([
        //     'status' =>"notbooked",
        //     'time' => array_slice($timeArray, 0, 10),
            

        // ]);

        if($today == $request->date){
            $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
            $display_time =
                                         array_filter($timeArray, function($e) use($now){
                                      
                              
                                          $hour = date("H:i", strtotime($e));
                                          $delimiter = ':';
                                         $words = explode($delimiter, $hour);
                                         
                                             $mytimed = $words[0];
                                             
                                         
                                                 if($mytimed > $now){
                                                     return $e;
                                         
                                         
                              
                                         
                                      }
                              
                              
                                    
                                  });
 
                                  
                                     return array(
                                         'status'=>'success',
                                          'available' => array_slice($display_time, 0, 10),
                                         
                                         );
                                  
        }

        else{
            return array(
                'status'=>'success',
                 'available' => array_slice($timeArray, 0, 10),
                
                );
        }

        

                                 
    }


}


//get OWC doctors by specialization 

public function getDoctorSpec(Request $request){
    $validator = Validator::make($request->all(), [
       
       
        'spec'=> 'required',
       
       
        
]);


    if($validator->fails()){
    return response([
        'status' => 'failed',
        'message' => $validator->errors()
    ]);
    }


    if($request->spec == "General Practitioner"){

        $doctor = User::where("hospital","One Wellness Clinic")->where("aos","General Practitioner")->get();


        return response()->json([
            'status' => 'success',
            'doctors' => $doctor,
        ]);
    }

    elseif($request->spec == "Gynaecologist"){
        $doctor = User::where("hospital","One Wellness Clinic")->where("aos","Gynaecology")->get();


        return response()->json([
            'status' => 'success',
            'doctors' => $doctor,
        ]);
    }



}


// OWC Schedule manage time

public function owcmanagetime($id){


   
    $schedule = Owcalendar::find($id);
    $blocked_date = $schedule->date;
    $blocktimes = Owctime::where("date",$blocked_date)->where("specialization",$schedule->specialization)->get();
    return view("owcscheduletime",compact("schedule","blocktimes"));
}

//OWC schedule manage time post

public function owcmanagetimepost(Request $request){

    if(!$request->time){
        return back()->with("error","Kindly select at least a time");
    }
   
    $date = Carbon::createFromFormat('d/m/Y',$request->date)->format('l, F d, Y');
     //dd($date);
    foreach($request->time as $times){
        //dump($times);

        $existed = Owctime::where("date",$request->date)->where("time",$times)->where("specialization",$request->specialization)->exists();

        // if($existed){
        //     $mytimess = Owctime::where("date",$request->date)->where("time",$times)->get();

        //     dd($mytimess);
        // }

        if(!$existed){
            
            $blocktime = new Owctime;
            $blocktime->date = $request->date;
           $blocktime->date_word = $date;
           $blocktime->time = $times;
           $blocktime->specialization = $request->specialization;

           $blocktime->save();

        }
    }

    return back()->with("msg","The selected time has been blocked successfully");


}


// delete owc managed time

public function owcmanagetimedelete(Request $request, $id){
    
    $time = Owctime::find($id)->delete();

    return back()->with("msg","Time Unblocked successfully");
}


//owc website api get blocked time

public function getblockedtime(Request $request){
    $validator = Validator::make($request->all(), [
       
       
        'date'=> 'required',
       
        'type' => 'required',
        
        
]);


    if($validator->fails()){
    return response([
        'status' => 'failed',
        'message' => $validator->errors()
    ]);
    }

    $time = Owctime::where("date_word",$request->date)->where("specialization",$request->type)->get();
    $blocked = [];

    foreach($time as $times){
        array_push($blocked,$times["time"]);
    }

    return $blocked;
}

public function getmostdate(Request $request){
    //

  $calendar = Owcalendar::where("specialization",$request->specialization)->get();

  

    //return $calendar;

    $dateArray = [];

    foreach($calendar as $date){
      $mydate = $date->date;

      
          array_push($dateArray,$mydate);
    }

     return $dateArray;
}





}