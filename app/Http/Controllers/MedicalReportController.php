<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Healthreports;
use App\Models\User;

class MedicalReportController extends Controller
{
    //createnewrecord

    public function createnewrecord(Request $request){
        $report = new Healthreports;

       
        
        $report->firstname = $request->firstname;
        $report->lastname = $request->lastname;
        $report->email = $request->email;
        $report->dob = $request->dob;
        $report->gender = $request->gender;

        // vitals 

        $report->weight = $request->weight;
        $report->height = $request->height;
        $report->temperature = $request->temperature;
        $report->pulse_rate = $request->pulse_rate;
        $report->blood_pressure = $request->blood_pressure;
        $report->respiratory_rate = $request->respiratory_rate;

        // symptoms

        $report->symptoms = json_encode($request->symptomsList);
        $report->history_of_complaints = $request->historyOfCompliants;
        $report->allergies = json_encode($request->allergies);

          // diagnosis

        $report->diagnosis = json_encode($request->diagnosesList);
        $report->other_diagnosis = json_encode($request->otherDiagnosis);

        //procedures

        $report->laboratory = json_encode($request->laboratory);
        $report->xray = json_encode($request->xray);
        $report->ultrasound = json_encode($request->ultrasound);

         //prescription

       
        $report->prescriptions = json_encode($request->prescriptions);
        $report->prescribed_by = $request->doctor_uuid;

         //followup

        $report->require_followup = $request->require_followup;

        if($request->require_followup == true){
            $report->followup_date = $request->followup_date;
            $report->followup_time = $request->followup_time;
            $report->followup_doctorId = $request->followup_doctorId;
            
        }

          //appointmentt ref

        $report->appointment_ref = $request->appointment_ref;

         // doctor

        $report->doctor_uuid = $request->doctor_uuid;
        $report->doctor_firstname = $request->doctor_firstname;
        $report->doctor_lastname = $request->doctor_lastname;
        $report->doctor_title = $request->doctor_title;


        $report->save();

        $appointment = Appointment::where("ref_no",$request->appointment_ref)->update([
          "healthrecord" => $request->appointment_ref
        ]);


        return response()->json("Consultation Records has been submitted succesfully");
    }


    //delete medical records

    public function deleterecords(Request $request){
      $record = Healthreports::truncate();

      return response()->json("Record deleted successfully");
    }

    // get healthrecords by ref

    public function gethealthrecordbyref($ref){
      $record = Healthreports::where("appointment_ref",$ref)->first();

      return response()->json($record);
    }


    //update vitals healthreports

    // public function updatevitals(Request $request, $ref){

    //   $record = Healthreports::where("appointment_ref",$ref)->first();

    //   return response()->json($record);
    //   // $record = Healthreports::where("appointment_ref",$ref)->update([
        
    //   //   "weight" => $request->weight,
    //   //   "height" => $request->height,
    //   //   "temperature" => $request->temperature,
    //   //   "pulse_rate" => $request->pulse_rate,
    //   //   "blood_pressure" => $request->blood_pressure,
    //   //   "respiratory_rate" => $request->respiratory_rate,
    //   // ]);

    //   // return response()->json("Vitals updated successfully");
    // }

   public function updatevitalsigns(Request $request, $ref){
       $record = Healthreports::where("appointment_ref",$ref)->update([
        
        "weight" => $request->weight,
        "height" => $request->height,
        "temperature" => $request->temperature,
        "pulse_rate" => $request->pulse_rate,
        "blood_pressure" => $request->blood_pressure,
        "respiratory_rate" => $request->respiratory_rate,
      ]);

      return response()->json("Vitals updated successfully");
    
   }

  //  update symptoms health records

  public function updatesymptoms(Request $request, $ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
      "symptoms" => json_encode($request->symptomsList),
    ]);

    return response()->json("Symptoms updated successfully");
  }

  //medical report controller update history

  public function updatehistory(Request $request, $ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
     "history_of_complaints" => $request->historyOfCompliants,
    ]);

    return response()->json("History of Complaints updated successfully");
  }

  //update allergies

  public function updateallergies(Request $request, $ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
      "allergies" => json_encode($request->allergies),
     ]);
 
     return response()->json("allergies updated successfully");
  }

  //update diagnosis

  public function updatediagnosis(Request $request,$ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
    "diagnosis" => json_encode($request->diagnosesList),
      "other_diagnosis" => json_encode($request->otherDiagnosis),
     ]);
 
     return response()->json("Diagnosis updated successfully");
  }

  // update laboratory

  public function updatelaboratory(Request $request,$ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
      "laboratory" => json_encode($request->laboratory),
       ]);
   
       return response()->json("Diagnosis updated successfully");
  }


  //update xray

  public function updatexray(Request $request,$ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
      "xray" => json_encode($request->xray),
       ]);
   
       return response()->json("Diagnosis updated successfully");
  }

  //update ultrasound

  public function updateUltrasound(Request $request,$ref){
    $record = Healthreports::where("appointment_ref",$ref)->update([
      "ultrasound" => json_encode($request->ultrasound),
       ]);
   
       return response()->json("Ultrasound updated successfully");
  }



  //get doctor and user details 

  public function gethealthrecordbyrefdetails(Request $request,$ref){
    $record = Healthreports::where("appointment_ref",$ref)->first();

    $user = User::where("email",$record->email)->value("uuid");

    $doctor= User::where("uuid",$record->followup_doctorId)->first();


   

   // doctor: `${doctortitle}. ${docfirstname} ${doclastname}`,
        //             aos:aos,
        //             doctormail: docemail,
        //             username: `${userfirstname} ${userlastname}`,  done
                   
        //             useremail: useremail,                           done
        //             usergender:usergender,                           done
        //             user_uuid:user_uuid,                             
        //             doctor_id:doctor_id,
        //             doctorfee:doctor?.fee,
        //             title: doctortitle,



    return response()->json([
      "useremail" => $record->email,
      "username" => $record->firstname.' '.$record->lastname,
      "usergender" => $record->gender,
      "user_uuid" => $user,
      "aos" => $doctor->aos,
      "doctormail" => $doctor->email,
      "doctor_id" => $doctor->id,
      "doctorfee" => $doctor->fee,
      "title" => $doctor->title,
      "doctor" => $doctor->title.' '.$doctor->firstname.' '.$doctor->lastname,
      "doctor_uuid" => $doctor->uuid,
    ]);
  }


}
