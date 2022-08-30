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

class EmbanqoController extends Controller
{
    //

    //get online doctors,, acceptes specialization and date

    public function getonlinedoctors(Request $request){

        $validator = Validator::make($request->all(), [
                 'specialization'=> "required",
                    'date'=> 'required|date_format:d-m-Y|after_or_equal:today',
        ]);

        if($validator->fails()){
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        $today = Carbon::parse($request->date);
        //get date day name

        $dayname = Carbon::parse($request->date)->format('l');


        //get next date
         $plusoneday = $today->addDays(1);

         //get next day format ('d-m-y')
         $plusonedayformat = Carbon::parse($plusoneday)->format('d-m-Y');

         //get next day day name

         $plusonedayname = Carbon::parse($plusoneday)->format('l');

        //return $plusonedayname;


        // $doctors = DocSchedule::where('day',$dayname)->where('specialization',$request->specialization)->distinct()->get(['doc_uuid']);

        $doctors = DocSchedule::where('specialization',$request->specialization)->where(function($q) use($dayname,$plusonedayname){
            $q->where('day',$dayname)->orwhere('day',$plusonedayname);
        })->distinct()->get(['doc_uuid']);

       $onlinedoc = [];
       foreach($doctors as $doc){
        $user = User::where('uuid',$doc->doc_uuid)->first();

        $docday;


        //check if doc schedule where day is equal to dayname and doc uuid is equal to doc uuid exists.

        $existed = DocSchedule::where('doc_uuid',$doc->doc_uuid)->where('day',$dayname)->exists();

        if($existed){
            $docday = $request->date;
             $time = DocSchedule::where('doc_uuid',$doc->doc_uuid)->where('day',$dayname)->pluck('time');
        }

        else if(!$existed){
            $docday = $plusonedayformat;
             $time = DocSchedule::where('doc_uuid',$doc->doc_uuid)->where('day',$plusonedayname)->pluck('time');
        }

    
        


        $onlinedoc[] = array(
     

     'uuid' => $doc->doc_uuid,
     'firstname' => $user->firstname,
     'lastname' => $user->lastname,
     'email' =>  $user->email,
     'phone' => $user->phone,
     'specialization' => $user->aos,
     'fee' => $user->fee,
    'date' => $docday,
    

     'time' => $time,
        );

       }

       return array(
        'docss' => $onlinedoc
       );
// foreach($doctors as $key=> $doc){
//     print "$key = $doc\n";

 
//    ///dump($doc->doc_uuid);
//     // $user = User::where('uuid',$doc->doc_uuid)->first();
//     // $time = DocSchedule::where('doc_uuid',$doc->doc_uuid)->where('day',$dayname)->pluck('time');

//     // $namee = response()->json([
//     //     'doctor' => $user,
//     //     'time' => $time
//     // ]);

//     // dump($namee);
// }

   







    }



    //draftOnline Booking

    public function draftonlinebooking(Request $request){
        $validator = Validator::make($request->all(), [
       
               'date'=> 'required|date_format:d-m-Y|after_or_equal:today',
               'time' => 'required',
               'phone' => 'required|exists:users',
               'uuid' =>'required|exists:users',
               'reason' => 'required',
               'fee' => 'required'
   ]);


   if($validator->fails()){
    return response([
        'status' => 'failed',
        'message' => $validator->errors()
    ]);
}


//generate template random id

$min = 20000000;
$max = 99999999;

$rand = rand($min, $max);

$temp_id = strval($rand);


//crate the temp appointment


$appointment  = new TempAppointment;
$appointment->temp_id = $temp_id;
$appointment->phone = $request->phone;
$appointment->doc_uuid = $request->uuid;
$appointment->reason = $request->reason;
$appointment->date = $request->date;
$appointment->time = $request->time;
$appointment->fee = $request->fee;

$appointment->save();

$appointment->refresh();

return response()->json($appointment);





    }


    //complete Online Booking Doctor

    public function completeOnlineBooking(Request $request){
        $validator = Validator::make($request->all(), [
       
           
            'temp_id' => 'required|exists:temp_appointments',
            'paystack_ref' =>'required',
            'cost' => 'required'
           
]);


            if($validator->fails()){
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
            }
            
            //Check if appointment where date is date, time is time, doctor is doctor exists
            $date= TempAppointment::where('temp_id',$request->temp_id)->value('date');

            $time= TempAppointment::where('temp_id',$request->temp_id)->value('time');

            $doctor_uuid = TempAppointment::where('temp_id',$request->temp_id)->value('doc_uuid');

            $reason = TempAppointment::where('temp_id',$request->temp_id)->value('reason');

            $user_phone = TempAppointment::where('temp_id',$request->temp_id)->value('phone');

            $doctor = User::where('uuid',$doctor_uuid)->first();

            $user = User::where('phone',$user_phone)->first();

            $doc_id = $doctor->id;

           
            $date = Carbon::parse($date)->format('Y-m-d');

          
            $doctorname = 'Dr. ' .$doctor->firstname.' ' .$doctor->lastname;



           $link = "https://meet.jit.si/asknello/".$request->paystack_ref;


            
          //Add Appointment Columns

          $appointment = new Appointment();

          $appointment->user_uuid = $user->uuid;
          $appointment->status = "pending";
          $appointment->date =$date;
          $appointment->time = $time;
          $appointment->location = "Online Scheduled Meeting";
          $appointment->ref_no = $request->paystack_ref;
          $appointment->uuid = Str::uuid()->toString();
          $appointment->doctor_id = $doc_id;
          $appointment->type = 'doctor_appointment';
          $appointment->doctor_name = $doctorname;
          
          $appointment->doctor_aos = $doctor->aos;
          $appointment->link = $link;
          $appointment->reason = $reason;
            
         $appointment->save();
          
         $customerdetails = [
             'doctor' => $doctorname,
             'time' => $time,
             'date' => $date,
             'doctoraos' => $doctor->aos,
             "link"=>$link,
             'username' => $user->firstname,
          
         ];
         
    //      TransactionLog::create([
    //       'gateway_reference' => $request->ref_no,
    //       'system_reference' => $request->ref_no,
    //       'reason' => 'Doctor Appointment',
    //       'amount' => $request->amount,
    //       'email' => $request->user_email,
    //   ]);

    DB::table('transaction_logs')->insert([
                 'gateway_reference' => $request->paystack_ref,
          'system_reference' => $request->paystack_ref,
          'reason' => 'Doctor Appointment',
          'amount' => $request->cost,
          'email' => $user->email,
    ]);
      
  
    //   Mail::to($request->user_email)->send(new AppointmentCustomer($customerdetails));
         
    //   Mail::to($request->doctor_email)->send(new AppointmentDoctor($customerdetails));
         
  
          //$user->notify(new AppointmentBookedNotification($appointment));
          //SendAppointmentEmail::dispatch($appointment);
          //SendAppointmentEmail::dispatch();

          $delete = TempAppointment::where('temp_id',$request->temp_id)->delete();
          return [
  
              'status'=>true,
              "message"=>"Appointment Booked Successfully",
             
              "date"=>$date,
              "time"=>$time,
              "ref_no"=>$request->paystack_ref,
              "doctor_name"=>$doctorname,
              "doctor_aos"=>$doctor->aos,
              "link"=>$link,
              "user_email"=>$user->email,
              "doctor_email"=>$doctor->email,
              "username"=>$user->firstname,
  
          ];
         
  


          //Delete Temp appointment with temp_id

          // Send Mail to User

          //send Mail to doctor

          

            
    }

//get states
    public function getStates(){

        // $healthcenterstates = HealthCenter::pluck('state')->distinct()->get(['state']);

        // $healthcenterstates = HealthCenter::pluck('state')->distinct();
        $healthcenterstates = HealthCenter::select('state')->distinct()->get();

        return $healthcenterstates;

    }


    public function getLocations(Request $request){
        $validator = Validator::make($request->all(), [
       
            'state' => 'required',
            
]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }

        $healthcenterlocation = HealthCenter::select('city')->where('state',$request->state)->distinct()->get();


        return $healthcenterlocation;

       



    }


    //getfacilities

    public function getFacilities(Request $request){

        $validator = Validator::make($request->all(), [
       
            'state' => 'required',
            'location' => 'required',
            'specialization'=>'required'
            
        ]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }
        
       // MedSchedule

       $medical_Center = MedSchedule::where('state',$request->state)->where('specialization',$request->specialization)->where('lga',$request->location)->distinct()->get(['med_uuid']);

       

       $medicalcenter = [];
       foreach($medical_Center as $center){
        $Health = HealthCenter::where('uuid',$center->med_uuid)->first();

      

        $medicalcenter[] = array(
     

     'uuid' => $center->med_uuid,
     'name' => $Health->name,
     'type' => $Health->center_type,
     'location' =>  $Health->city,
     'address' => $Health->address1,
     'specialization' => $request->specialization,
    
        );

       }

       return array(
        'facilities' => $medicalcenter
       );

   




    }


    //check facility

    public function checkavailability(Request $request){
        

        $validator = Validator::make($request->all(), [
                    'specialization'=> "required",
                    'uuid' => 'required|exists:health_centers',
                    'date'=> 'required|date_format:d-m-Y|after_or_equal:today',
        ]);

        if($validator->fails()){
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }


        $dayname = Carbon::parse($request->date)->format('l');

        
        $date = Carbon::parse($request->date);
           //get next date
           $plusoneday = $date->addDays(1);

           //get next day format ('d-m-y')
           $plusonedayformat = Carbon::parse($plusoneday)->format('d-m-Y');
  
           //get next day day name
  
           $plusonedayname = Carbon::parse($plusoneday)->format('l');



           //get previous date

           $priviousday = $date->subDays(1);
           //get previous day format ('d-m-y)

           $prviousdayformat = Carbon::parse($priviousday)->format('d-m-y');

           //get privius day name

           $priviousdayname = Carbon::parse($priviousday)->format('l');




           //get add add two days

           $addtwodays = $date->addDays(2);

           //get plus 2 days formadt (dmy)
           $addtwodaysformat = Carbon::parse($addtwodays)->format('d-m-y');

           //get add two days name

           $addtwodaysname = Carbon::parse($addtwodays)->format('l');
          



           




        //check if the date is far enough 

        $now = Carbon::now();

        $diff = $date->diffInWeeks($now);

        
        //check if diff is greater than 0

        
        
        if($diff < 1 ){
            

            $check = MedSchedule::where('specialization',$request->specialization)->where('med_uuid',$request->uuid)->where(function($q) use($dayname,$plusonedayname){
                $q->where('day',$dayname)->orwhere('day',$plusonedayname);
              })->get();
     
              //get Fee
     
              $cost = HealthCenter::where('uuid',$request->uuid)->value('fee');
     
     
     
     
     
             $facility = [];
     
             foreach($check as $center){
                 //$Health = HealthCenter::where('uuid',$center->med_uuid)->first();
                 $dated;
     
                 if($center->day == $dayname){
                     $dated = $request->date;
                 }
     
                 else if($center->day == $plusonedayname){
                     $dated = $plusonedayformat;
                 }
     
     
                
     
         
                 $facility[] = array(
              
         
              'day' => $center->day,
              'time' => $center->time,
              'date' => $dated,
              'cost'=>$cost,
              
             
                 );
         
                }
     
            
     
                $today = Carbon::now()->format('d-m-Y');
                $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
     
                
     
             
     // $str = "12:00:00";
     // $delimiter = ':';
     // $words = explode($delimiter, $str);
     
     // return $words[0];
     
                $display_related_tags =
         array_filter($facility, function($e) use($now){
             // return $e != $found_tag['name'];
             // if($e['time'] != "12:00:00"){
             //     return $e;
             // }
     
             // if($e['date']==$now && $e['time'])
     
                 $hour = $e['time'];
                 $delimiter = ':';
     $words = explode($delimiter, $hour);
     
         $mytime = $words[0];
     
             if($e['date'] && $mytime > $now){
                 return $e;
     
                
     
                
             }
     
     
           
         });
     
           
         
                return array(
                 'available' => array_values($display_related_tags)
                );
     
        }

        else if($diff > 0){
            $check = MedSchedule::where('specialization',$request->specialization)->where('med_uuid',$request->uuid)->where(function($q) use($dayname,$plusonedayname,$addtwodaysname){
                $q->where('day',$dayname)->orwhere('day',$plusonedayname)->orwhere('day',$addtwodaysname);
              })->get();
     
              //get Fee
     
              $cost = HealthCenter::where('uuid',$request->uuid)->value('fee');
     
     
     
     
     
             $facility = [];
     
             foreach($check as $center){
                 //$Health = HealthCenter::where('uuid',$center->med_uuid)->first();
                 $dated;
     
                 if($center->day == $dayname){
                     $dated = $request->date;
                 }
     
                 else if($center->day == $plusonedayname){
                     $dated = $plusonedayformat;
                 }

                 else if($center->day == $addtwodaysname){
                    $dated = $addtwodaysformat;
                 }
     
     
                
     
         
                 $facility[] = array(
              
         
              'day' => $center->day,
              'time' => $center->time,
              'date' => $dated,
              'cost'=>$cost,
              
             
                 );
         
                }
     
            
     
                $today = Carbon::now()->format('d-m-Y');
                $now = Carbon::now()->timezone('Africa/Lagos')->format('H');
     
                
     
             
     // $str = "12:00:00";
     // $delimiter = ':';
     // $words = explode($delimiter, $str);
     
     // return $words[0];
     
                $display_related_tags =
         array_filter($facility, function($e) use($now){
             // return $e != $found_tag['name'];
             // if($e['time'] != "12:00:00"){
             //     return $e;
             // }
     
             // if($e['date']==$now && $e['time'])
     
                 $hour = $e['time'];
                 $delimiter = ':';
     $words = explode($delimiter, $hour);
     
         $mytime = $words[0];
     
             if($e['date'] && $mytime > $now){
                 return $e;
     
                
     
                
             }
     
     
           
         });
     
           
         
                return array(
                 'available' => array_values($display_related_tags)
                );
     
                 
        }

        
        
    }



    //draft facility temporary booking

    public function draftfacilitybooking(Request $request){
        $validator = Validator::make($request->all(), [
       
            'date'=> 'required|date_format:d-m-Y|after_or_equal:today',
            'time' => 'required',
            'phone' => 'required|exists:users',
            'uuid' =>'required|exists:health_centers',
            'reason' => 'required',
            'fee' => 'required'
]);


            if($validator->fails()){
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
            }


            //generate template random id

            $min = 20000000;
            $max = 99999999;

            $rand = rand($min, $max);

            $temp_id = strval($rand);

           
$dateformated = Carbon::parse($request->date)->format('Y-m-d');


$existed = Appointment::where('date',$dateformated)->where('time',$request->time)->where('center_uuid',$request->uuid)->exists();

if($existed){

    return response()->json([
        'status' => 'failed',
        'message' => "Appointment schedule already taken, select another time"
    ]);
}


            $appointment  = new TempAppointment;
$appointment->temp_id = $temp_id;
$appointment->phone = $request->phone;
$appointment->doc_uuid = $request->uuid;
$appointment->reason = $request->reason;
$appointment->date = $request->date;
$appointment->time = $request->time;
$appointment->fee = $request->fee;

$appointment->save();

$appointment->refresh();

return response()->json($appointment);

        

    }

//complete facility visit

public function completefacilityvisit(Request $request){

    $validator = Validator::make($request->all(), [
       
           
        'temp_id' => 'required|exists:temp_appointments',
        'paystack_ref' =>'required',
        'cost' => 'required'
       
]);


        if($validator->fails()){
        return response([
            'status' => 'failed',
            'message' => $validator->errors()
        ]);
        }


        $date= TempAppointment::where('temp_id',$request->temp_id)->value('date');

        $time= TempAppointment::where('temp_id',$request->temp_id)->value('time');

        $doctor_uuid = TempAppointment::where('temp_id',$request->temp_id)->value('doc_uuid');

        $reason = TempAppointment::where('temp_id',$request->temp_id)->value('reason');

        $user_phone = TempAppointment::where('temp_id',$request->temp_id)->value('phone');

        $healthcenter = HealthCenter::where('uuid',$doctor_uuid)->first();

        $user = User::where('phone',$user_phone)->first();

        // $doc_id = $doctor->id;

       
        $date = Carbon::parse($date)->format('Y-m-d');

      
        $centername = $healthcenter->name;



       $centeraddress = $healthcenter->address1;


        
      //Add Appointment Columns

      $appointment = new Appointment();

      $appointment->user_uuid = $user->uuid;
      $appointment->status = "pending";
      $appointment->date =$date;
      $appointment->time = $time;
      $appointment->location = $centeraddress;
      $appointment->ref_no = $request->paystack_ref;
      $appointment->uuid = Str::uuid()->toString();
      $appointment->center_uuid = $healthcenter->uuid;
      $appointment->type = 'hospital';
      $appointment->center_name = $centername;
      

      $appointment->reason = $reason;
        
     $appointment->save();
      
    //  $customerdetails = [
    //      'doctor' => $doctorname,
    //      'time' => $time,
    //      'date' => $date,
    //      'doctoraos' => $doctor->aos,
    //      "link"=>$link,
    //      'username' => $user->firstname,
      
    //  ];
     
//      TransactionLog::create([
//       'gateway_reference' => $request->ref_no,
//       'system_reference' => $request->ref_no,
//       'reason' => 'Doctor Appointment',
//       'amount' => $request->amount,
//       'email' => $request->user_email,
//   ]);

DB::table('transaction_logs')->insert([
             'gateway_reference' => $request->paystack_ref,
      'system_reference' => $request->paystack_ref,
      'reason' => 'Hospital Appointment',
      'amount' => $request->cost,
      'email' => $user->email,
]);
  

//   Mail::to($request->user_email)->send(new AppointmentCustomer($customerdetails));
     
//   Mail::to($request->doctor_email)->send(new AppointmentDoctor($customerdetails));
     

      //$user->notify(new AppointmentBookedNotification($appointment));
      //SendAppointmentEmail::dispatch($appointment);
      //SendAppointmentEmail::dispatch();

      $delete = TempAppointment::where('temp_id',$request->temp_id)->delete();
      return [

          'status'=>true,
          "message"=>"Appointment Booked Successfully",
         
          "date"=>$date,
          "time"=>$time,
          "ref_no"=>$request->paystack_ref,
          "center_name"=>$centername,
          "center_address"=>$centeraddress,
          "state"=>$healthcenter->state,
          "city"=>$healthcenter->city,
          "user_email"=>$user->email,
          "username"=>$user->firstname,

      ];
     



      //Delete Temp appointment with temp_id

      // Send Mail to User

      //send Mail to doctor

       
        




}


public function webhook(Request $request){
    WebhookCall::create()
   ->url('http://127.0.0.1:8000/webhook')
   ->payload([
            'status_code' => 200, 
            'status' => 'success',
            'message' => 'webhook send successfully',
            'extra_data' => [
                'first_name' => 'Harsukh',
                'last_name' => 'Makwana',
            ],
   ])
   ->useSecret('sign-using-this-secret')
   ->dispatch();
}


public function webhookreceive(Request $request){
    // Log::debug($request->body);
    // return response()->json(true);
    $test = file_get_contents('php://input');

    Log::debug($test);
}

}
