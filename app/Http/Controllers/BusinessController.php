<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Interest;
use App\Mail\AdminInterestMail;
use App\Mail\UserInterestMail;
use Mail;


class BusinessController extends Controller
{
    //interest create nello business

    public function interest(Request $request){

        $validator = Validator::make($request->all(), [

 
            "type" => "required",
            "business_name" => "required",
            "fullname" => "required",
            "email" => "required",
            "phone" => "required",
            "interestList" => "required",
            "others" => "required"
              
   ]);

            if($validator->fails()){
                return response([
                    'status' => 'failed',
                    'message' => $validator->errors()
                ]);
            }

           


           $interest = new interest;
           $interest->business_type = $request->type;
           $interest->business_name = $request->business_name;
           $interest->fullname = $request->fullname;
           $interest->email = $request->email;
           $interest->phone = $request->phone;
           $interest->interest = json_encode($request->interestList);
           $interest->others = $request->others;
           $interest->status = "pending";
           $interest->save();


           $admininterest = [
                'name' => $request->fullname,
                'email' => $request->email,
                'type' => $request->type,
                'interest' => json_encode($request->interestList),
                'others' => $request->others,

           ];

      

           if($request->type == "Healthcare Facilities(Hospital, Clinic, etc)"){
               $link = "https://docs.google.com/forms/d/e/1FAIpQLSdzvlLOKDR5013fievnqcApwZs4olZBfGxNbUhjH0QJJaHIHg/viewform?usp=sharing";
           }

           elseif($request->type == "Healthcare Practitioner(Doctor, Specialist, Nurse, etc)"){
               $link = "https://docs.google.com/forms/d/e/1FAIpQLScfzY3IHbtVAgOadFd3HYO5vYgx922nFSfjqtOjkt_ie2NAMg/viewform?usp=sharing";
           }

           elseif($request->type == "Pharmacy"){
               $link = "https://docs.google.com/forms/d/e/1FAIpQLSeAiRQnV3EPZTthT1qApuNJqvWzsd3p0i9n80H1n999vxGjlg/viewform?usp=sharing";
           }

           elseif($request->type == "Diagnostic Center"){
               $link = "https://docs.google.com/forms/d/e/1FAIpQLSemt85IVuTyJsrbguf_SBVxEmCc4bJw2NzjbXXp2INqV6iLHA/viewform?usp=sharing";
           }

           elseif($request->type == "Company"){
               $link = "https://docs.google.com/forms/d/e/1FAIpQLSfRyQJRRkikat0hx_dCJ2sARywQghiwX8i7z0KIpLHf95pMaw/viewform?usp=sharing";
           }
           else{
            $link = "https://docs.google.com/forms/d/e/1FAIpQLSfRyQJRRkikat0hx_dCJ2sARywQghiwX8i7z0KIpLHf95pMaw/viewform?usp=sharing";
           }


           $customer = [
               'name' => $request->fullname,
               'link' => $link
               
           ];

           Mail::to("support@asknello.com")->send(new AdminInterestMail($admininterest));
           Mail::to("chioma@asknello.com")->send(new AdminInterestMail($admininterest));

        Mail::to($request->email)->send(new UserInterestMail($customer));

           return response()->json([
               'status' => "success",
               'message' => 'Thanks for your interest to join Nello, A mail has been sent to you to kick-start.',
           ]);

    }


    //business interest page


    public function getinterest(){
        $interest = Interest::all();

        return view("interestpage",compact('interest'));
    }


}
