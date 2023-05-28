<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\HealthCenter;
use App\Specialization;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FamacareController extends Controller
{
    //famacare dashboard

    public function dashboard(){
        return view("famacaredashboard");
    }


    //famacare appointment

    public function appointment(){
        $appointments = Appointment::with(['user', 'center', 'doctor'])->get();

        $list = [];
        foreach($appointments as $appointment){

            //array_push($list ,$appointment);

            if($appointment->doctor){
               // array_push($list ,$appointment); 
               if($appointment->doctor->hospital == "Famacare Center Limited"){
                   //dd($appointment->doctor->hospital);
                    array_push($list ,$appointment); 
               }
            }

            
         }
        return view("famacareappointment",compact("list"));

     
    }


    //famacare physical appointment

    public function physicalappointment(){
        $appointments = Appointment::with(['user', 'center', 'doctor'])->get();

        $list = [];
        foreach($appointments as $appointment){

            //array_push($list ,$appointment);

            if($appointment->center){
               // array_push($list ,$appointment); 
               if($appointment->center->name == "Famacare Center Limited"){
                   //dd($appointment->doctor->hospital);
                    array_push($list ,$appointment); 
               }
            }

            
         }

         return view("famacarephysical",compact("list"));

        
    }


    //famacare specialist

    public function specialist(){
        $specialist = User::where("user_type","doctor")->where("hospital","Famacare Center Limited")->get();

        $count = User::where("user_type","doctor")->where("hospital","Famacare Center Limited")->count();

        return view("famacarespecialist",compact("specialist","count"));



    }


    //famacare specialization page

    public function specialization(){
        $specialization = Specialization::where("med_center_uuid","24592144-b717-45bd-9fbf-905d3a26ee84")->get();

        //dd($specialization);
        return view("famacarespecialization",compact("specialization"));
    }



}
