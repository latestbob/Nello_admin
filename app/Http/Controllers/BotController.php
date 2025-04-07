<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Botmessage;
use App\Models\User;
use Carbon\Carbon;


require_once app_path('Helpers/MessageHelper.php');

use App\Helpers\MessageHelper;


class BotController extends Controller
{
    //

    public function bothook(Request $request){
        if($request->isMethod('post')){
            
            $token = MessageHelper::getToken();

            $response = $request->getContent();

            
            // Decode the JSON response to an associative array
            $data = json_decode($response, true);
            
            Log::debug($response);

            //$nextstep = $data['parent_param']['next_step'];
            $to = $data['to'];
            $text = $data['text'];
            $payload = $data['payload'];

            $nextstep = Botmessage::where("wa_id",$to)->value('next_step');
            $parent_param = Botmessage::where('wa_id',$to)->value("parent_param");
            $profileName = Botmessage::where("wa_id",$to)->value("profile");
            $action = Botmessage::where("wa_id",$to)->value("action");
            $user_email = Botmessage::where("wa_id",$to)->value("quick_reply");

            $error_count = Botmessage::where("wa_id",$to)->value("error");

            Log::debug($nextstep);
            Log::debug($profileName);
            Log::debug($error_count);

            if($error_count == 2){

                //handle error limit

                //update next step to null
                // update parent params to Null

                $update = Botmessage::where("wa_id",$to)->update([

                    'next_step' => "handle_services",
                    'parent_param' => null,
                    'error' => 0,
                    'action' => null,

                ]);

                //call the handleErrorLimit from helper class
               MessageHelper::handleErrorLimit($token, $to);


            


            }

            else{

            

            



            switch ($nextstep) {
                case null:
                    // if next sttep is null

                    //check if the user enter Hi, Hello, Get Started or Start

                    if($text === "Hi" || $text === "Hello" || $text === "Get Started"){
                        
                        //trigger Bot Welcome Message

                        MessageHelper::sendTemplateMessage($token, $to);

                        //update next step

                        $next_step = "handle_services";
                        

                     MessageHelper::updateNextStep($next_step, $to, null);

                      


                    }



                   


                    break;

                case "handle_services":
                    
                    //code

                    //check if user is authenticated

                    if($payload == ""){

                        $error = Botmessage::where('wa_id',$to)->update([
                            'error' => $error_count + 1,
                            'next_step' => "handle_services",
                        ]);


                        if($error < 2){
                            MessageHelper::sendTemplateMessage($token, $to);
                        }



                    }
                   

                    else {

                    
                   

                    

                    $auth = Botmessage::where('wa_id',$to)->value("isAuth");

                    

                    switch ($text) {


                        case "Online Consultation":
                            // code... when the user is not authenticated

                            $update_action = Botmessage::where("wa_id",$to)->update([
                                'action' => "Online Consultation",
                            ]);

                            if($auth == null){

                                //as the use to provide their email registered on Nello

                                //send message 
                                $textBody = "Kindly provide your email registered on Nello";

                                MessageHelper::sendTextMessage($token, $to, $textBody);



                                //updated next step

                                $next_step = "check_email_consultation";

                                MessageHelper::updateNextStep($next_step, $to, null);







                            }



                            //code .. when user is authenticated

                            else if($auth == true){

                                 //get request to get all specialization on Nello
                            $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');

                            $resjson = $response_spec->json();

                            //Log::debug($resjson);
                            $formattedArray = [];

                            // Iterate over each item in the $resjson array
                            foreach ($resjson as $doc_spec) {
                                $formattedArray[] = [
                                    "type" => "reply",
                                    "reply" => [
                                        "id" => $doc_spec['aos'],
                                        "title" => substr($doc_spec['aos'],0, 20),
                                    ]
                                ];
                            }



                        
                            $textBody = "Kindly select the kind of specialist that you’d like to consult with";

                            MessageHelper::sendInteractiveReply($token, $to, $textBody, $formattedArray);


                            $next_step = "handle_specialist_consultation";

                            MessageHelper::updateNextStep($next_step, $to, null);


                            }
                            
                    




                            break;


                        case 'Speak to an Agent':
                                # code... medical facility action
                                $update_action = Botmessage::where("wa_id",$to)->update([
                                    'action' => "Speak to an Agent",
                                ]);


                                $textBody = "Sure, I can help you connect to a Nello Support Agent. How would you like to be contacted?";


                                $reply_array = [

                                    [
                                        "type" => "reply",
                                        "reply" => [
                                          "id" => "WhatsApp Call",
                                          "title" => "WhatsApp Call"
                                        ]
                                    ],
            
                                    [
                                        "type" => "reply",
                                        "reply" => [
                                          "id" => "Chat",
                                          "title" => "Chat"
                                        ]
                                    ],
            
                                ];
            
                            
                                MessageHelper::sendInteractiveReply($token, $to, $textBody, $reply_array);
            
            
            
            
                                // //next step will be to collect their date of birth
                                // //
            
                               
                                // $param = json_decode($parent_param, true);

                                // if (is_null($param)) {
                                //     $param = [];
                                // }
            
                                // $new_param = [
                                //     "dob" => $text,
                                // ];
            
                                $next_step = "handle_reach_agent";
                                // $parent_param = array_merge($param, $new_param);
            
                                MessageHelper::updateNextStep($next_step, $to, null);

                                




                                break;


                               

                                    //break

                        case 'Provide Feedback':
                            # code... Provide Feedback Action

                            $update_action = Botmessage::where("wa_id",$to)->update([
                                'action' => "Provide Feedback",
                            ]);


                            $textBody = "We would love to hear your feedback! Please provide your email address for follow-up.";

                            MessageHelper::sendTextMessage($token, $to, $textBody);

                            //update next step


                            $next_step = "handle_email_feedback";
                            // $parent_param = array_merge($param, $new_param);
        
                            MessageHelper::updateNextStep($next_step, $to, null);
                           
                            break;

                     case 'Reschedule Appointment':


                        //code here for reschedule appointment here


                        $update_action = Botmessage::where("wa_id",$to)->update([
                            'action' => "Reschedule Appointment",
                        ]);





                        break;
                        
                        default:
                            # code...
                            break;
                    }

                }
                    break;

               

                
            case "check_email_consultation":

                //check user input and validate email

                if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
                   //if email is validated

                   //check if user is registered on Nello with same email

                   $user = User::where('email',$text)->exists();


                    if(!$user){
                        //when user not existed

                        //send a message teling them that they are not are registered user

                        $textBody = "Your email isn't registered on Nello. Follow few steps to sign up";

                        MessageHelper::sendTextMessage($token, $to, $textBody);

                        //and that they should few the follow few steps to register in no time

                        $textBody = "Kindly provide your first name";
                        MessageHelper::sendTextMessage($token, $to, $textBody);




                        //next step will be to collect their firstname
                        //

                        $next_step = "handle_firstname_consultation";
                        $parent_param = [
                            "email" => $text,
                        ];

                        MessageHelper::updateNextStep($next_step, $to, $parent_param);
                    }

                    else{
                        //when user is registered

                        //get firstname

                        $firstname = User::where('email',$text)->value('firstname');


                        // welcome them,,, and let them know that this steps only happen once

                        $textBody = "Hi $firstname,\n\nWelcome Back! 🎉 You’re now signed in on Nello. You won't need to go through the previous steps again.\n\nWelcome to a simpler, smarter healthcare experience!";


                            $updateAuth = Botmessage::where('wa_id',$to)->update([
                                'isAuth' => true,
                                'quick_reply' => $text,
                            ]);


                            MessageHelper::sendTextMessage($token, $to, $textBody);



                            //get request to get all specialization on Nello
                            $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');

                            $resjson = $response_spec->json();

                            //Log::debug($resjson);
                            $formattedArray = [];

                            // Iterate over each item in the $resjson array
                            foreach ($resjson as $doc_spec) {
                                $formattedArray[] = [
                                    "type" => "reply",
                                    "reply" => [
                                        "id" => $doc_spec['aos'],
                                        "title" => substr($doc_spec['aos'],0, 20),
                                    ]
                                ];
                            }



                        
                            $textBody = "Kindly select the kind of specialist that you’d like to consult with";

                            MessageHelper::sendInteractiveReply($token, $to, $textBody, $formattedArray);


                            $next_step = "handle_specialist_consultation";

                            MessageHelper::updateNextStep($next_step, $to, null);



                        // ask them to choose care they would like



                    }
                   

                   


                } else {
                    
                    // email not validated
                    
                    //update error count

                    $error = Botmessage::where('wa_id',$to)->update([
                        'error' => $error_count + 1,
                        'next_step' => "check_email_consultation",
                    ]);

                    $textBody = "You've entered an invalid email format, Input a valid email address";

                    if($error < 2){
                        MessageHelper::sendTextMessage($token, $to, $textBody);
                    }
                    



                }





                break;


            case "handle_firstname_consultation":

                //handle firstnme consultation validateion


                if(preg_match("/^([a-zA-Z' ]+)$/",$text)){
                    //if firstname format is validate

                    $textBody = "Kindly provide your last name";
                    MessageHelper::sendTextMessage($token, $to, $textBody);




                    //next step will be to collect their lastname
                    //

                    $param = json_decode($parent_param, true);

                    $new_param = [
                        "firstname" => $text,
                    ];

                    $next_step = "handle_lastname_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);
                }

                else{

                    //if firtnname format is not valid

                    $error = Botmessage::where('wa_id',$to)->update([
                        'error' => $error_count + 1,
                        'next_step' => "handle_firstname_consultation",
                    ]);

                    $textBody = "Invalid input format, enter a valid firstname";

                    if($error < 2){
                        MessageHelper::sendTextMessage($token, $to, $textBody);
                    }



                }
                

                break;

            case "handle_lastname_consultation":

                //handle lastname consultation

                if(preg_match("/^([a-zA-Z' ]+)$/",$text)){
                    //if lastname format is validate

                    $textBody = "Kindly provide your phone number";
                    MessageHelper::sendTextMessage($token, $to, $textBody);




                    // //next step will be to collect their phone
                    // //

                    $param = json_decode($parent_param, true);

                    $new_param = [
                        "lastname" => $text,
                    ];

                    $next_step = "handle_phone_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);
                }

                else{

                    //if lastname format is not valid

                    $error = Botmessage::where('wa_id',$to)->update([
                        'error' => $error_count + 1,
                        'next_step' => "handle_lastname_consultation",
                    ]);

                    $textBody = "Invalid input format, enter a valid lastname";

                    if($error < 2){
                        MessageHelper::sendTextMessage($token, $to, $textBody);
                    }



                }
                



                break;

            case "handle_phone_consultation":

                // handle phone number consultation

                if(preg_match('/^[0-9]{11}+$/', $text)){
                    //if phone format is validate

                    //check if phone number is already assoicated with a user

                    $user = User::where("phone",$text)->exists();

                    if($user){
                        //if the phone number already registered with a user

                        $error = Botmessage::where('wa_id',$to)->update([
                            'error' => $error_count + 1,
                            'next_step' => "handle_phone_consultation",
                        ]);
    
                        $textBody = "This phone number is already in use. Kindly provide another one";
    
                        if($error < 2){
                            MessageHelper::sendTextMessage($token, $to, $textBody);
                        }




                    }

                    else{
                        //if phone number not already used

                        $textBody = "Provide date of birth in this format : dd/mm/yyyy";
                    MessageHelper::sendTextMessage($token, $to, $textBody);




                    // //next step will be to collect their date of birth
                    // //

                    $param = json_decode($parent_param, true);

                    $new_param = [
                        "phone" => $text,
                    ];

                    $next_step = "handle_dob_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);
                    }

                    
                }

                else{

                    //if phnoe format is not valid

                    $error = Botmessage::where('wa_id',$to)->update([
                        'error' => $error_count + 1,
                        'next_step' => "handle_phone_consultation",
                    ]);

                    $textBody = "Invalid input format, enter a valid phone number";

                    if($error < 2){
                        MessageHelper::sendTextMessage($token, $to, $textBody);
                    }



                }




                break;

            case "handle_dob_consultation":

                //handle date of birth consultation


                if (preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d\d$/", $text)) {
                    // The date of birth is in the correct format (dd/mm/yyyy)


                    $reply_array = [

                        [
                            "type" => "reply",
                            "reply" => [
                              "id" => "Male",
                              "title" => "Male"
                            ]
                        ],

                        [
                            "type" => "reply",
                            "reply" => [
                              "id" => "Female",
                              "title" => "Female"
                            ]
                        ],

                    ];

                    $textBody = "Select your gender";
                    MessageHelper::sendInteractiveReply($token, $to, $textBody, $reply_array);




                    // //next step will be to collect their date of birth
                    // //

                    $param = json_decode($parent_param, true);

                    $new_param = [
                        "dob" => $text,
                    ];

                    $next_step = "handle_gender_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);
                 }


                 else {


                    $error = Botmessage::where('wa_id',$to)->update([
                        'error' => $error_count + 1,
                        'next_step' => "handle_dob_consultation",
                    ]);

                    $textBody = "Invalid date input, kindly input a valid input with format (dd/mm/yyyy)";

                    if($error < 2){
                        MessageHelper::sendTextMessage($token, $to, $textBody);
                    }

                    // The date of birth is not in the correct format



                }


                break;


                case "handle_gender_consultation":

                    //handle gender connsultation

                    $genders = ["Male", "Female"];

                    
                        

                        if (in_array($text, $genders)) {
                            // if the user select from gender

                           
                            //generate a password button

                            $param = json_decode($parent_param, true);

                                $new_param = [
                                    "gender" => $text,
                                ];

                                $next_step = "handle_password_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);



                                    $textBody = "Kindly click on the below button to setup your password";
                                    $url = "https://utils.asknello.com//?phoneId=".urlencode($to);
                                    $btnText = "Create Password";

                                    MessageHelper::sendBtnCallToActionMessage($token, $to, $textBody, $url, $btnText);


                    

                    




                            
                        } else {
                            // if user input the wrong gender or type something else


                            $error = Botmessage::where('wa_id',$to)->update([
                                'error' => $error_count + 1,
                                'next_step' => "handle_gender_consultation",
                            ]);
        
                            $reply_array = [

                                [
                                    "type" => "reply",
                                    "reply" => [
                                      "id" => "Male",
                                      "title" => "Male"
                                    ]
                                ],
        
                                [
                                    "type" => "reply",
                                    "reply" => [
                                      "id" => "Female",
                                      "title" => "Female"
                                    ]
                                ],
        
                            ];
        
                            $textBody = "Invalid input. Choose a gender from the provided list";
                            
        
                            if($error < 2){
                                MessageHelper::sendInteractiveReply($token, $to, $textBody, $reply_array);
                            }
                            
                        }



                    break;


                case "handle_specialist_consultation":

                    //check specialis type is valid,,make sure user clicked on the geneted one

                    Log::debug($payload);

                    $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');

                            $resjson = $response_spec->json();

                            

                            $found = false;

                                // Iterate through the array
                                foreach ($resjson as $item) {
                                    if (strcasecmp($item['aos'], $payload) === 0) {
                                        // Found a match
                                        $found = true;
                                        break;
                                    }
                                }



                           // Log::debug($specialist);
                            //if($payload == "General Practitioner(GP)"){

                            if ($found) {

                                //if the user entered correctly

                                $param = json_decode($parent_param, true);

                                if (is_null($param)) {
                                    $param = [];
                                }
                        

                                $new_param = [
                                    "specialist" => $payload,
                                ];

                               

                   

                    $textBody = "Kindly choose preferred date for appointment with a ".$text;
                    $header = 'Choose Preferred Date';
                    $btnText = "Choose Date";


                    //get date from nelllo

                    $response_dates = Http::get('https://admin.asknello.com/api/specialistschedule?specialization='.$payload);
    
                    $resjson = $response_dates->json();

                    // Log::debug($resjson);
                    $specialization_dates = [];

                    foreach($resjson  as $doc_dates){
                       

                        $specialization_dates[] = [
                           "id" => $doc_dates,
                            "title" => $doc_dates,
                            "description" => ""
                        ];
                    } 
                    


                    MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $specialization_dates, $btnText);

                    $next_step = "handle_date_consultation";
                    $parent_param = array_merge($param, $new_param);

                    MessageHelper::updateNextStep($next_step, $to, $parent_param);

                            }

                            else{
                                //if the user enter a wrong specialist


                                $error = Botmessage::where('wa_id',$to)->update([
                                    'error' => $error_count + 1,
                                    'next_step' => "handle_specialist_consultation",
                                ]);


                                      //get request to get all specialization on Nello
                            $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');

                            $resjson = $response_spec->json();

                            //Log::debug($resjson);
                            $formattedArray = [];

                            // Iterate over each item in the $resjson array
                            foreach ($resjson as $doc_spec) {
                                $formattedArray[] = [
                                    "type" => "reply",
                                    "reply" => [
                                        "id" => $doc_spec['aos'],
                                        "title" => substr($doc_spec['aos'],0, 20),
                                    ]
                                ];
                            }



                        
                            $textBody = "Invalid input, select the kind of specialist from the generated list";


                            if($error < 2){
                            MessageHelper::sendInteractiveReply($token, $to, $textBody, $formattedArray);

                            }
            

                            }



                   

                    break;

                

                case "handle_date_consultation":

                    //code here check if user choose the right date from the list
                    $param = json_decode($parent_param, true);
                    $specialist = $param['specialist'];


                    $response_dates = Http::get('https://admin.asknello.com/api/specialistschedule?specialization='.$specialist);
    
                    $dates = $response_dates->json();



                    if(in_array($text, $dates)){

                        // user input the correct date from the list of generated



                        $param = json_decode($parent_param, true);

                        // if (is_null($param)) {
                        //     $param = [];
                        // }
                

                        $new_param = [
                            "date" => $text,
                        ];

                       

           

            $textBody = "Kindly chooose preferred time for appointment on";
            $header = 'Choose Preferred Time';
            $btnText = "Choose Time";


            //get time associated with selected date from nelllo

            $response_dates = Http::get('https://admin.asknello.com/api/specialistscheduletime?specialization='.$specialist.'&date='.$text);

            $resjson = $response_dates->json();

            Log::debug($response_dates);
            Log::debug($resjson);


            $specialization_time = [];

            foreach($resjson['available']  as $doc_time){
               

                $specialization_time[] = [
                   "id" => $doc_time,
                    "title" => $doc_time,
                    "description" => ""
                ];
            } 
            


            MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $specialization_time, $btnText);



            $next_step = "handle_time_consultation";
            $parent_param = array_merge($param, $new_param);

            MessageHelper::updateNextStep($next_step, $to, $parent_param);


                    }

                    else{
                        //if the date is note found, that means user input the wrong date

                        $error = Botmessage::where('wa_id',$to)->update([
                            'error' => $error_count + 1,
                            'next_step' => "handle_date_consultation",
                        ]);


                        $textBody = "Invalid input, Kindly choose from the generated list";
                    $header = 'Choose Preferred Date';
                    $btnText = "Choose Date";


                    //get date from nelllo

                    $response_dates = Http::get('https://admin.asknello.com/api/specialistschedule?specialization='.$specialist);
    
                    $resjson = $response_dates->json();

                    // Log::debug($resjson);
                    $specialization_dates = [];

                    foreach($resjson  as $doc_dates){
                       

                        $specialization_dates[] = [
                           "id" => $doc_dates,
                            "title" => $doc_dates,
                            "description" => ""
                        ];
                    } 
                    

                    if($error < 2){
                    MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $specialization_dates, $btnText);
                    }

                    }


                    break;


                case "handle_time_consultation":

                    //handle time selected consultation

                    $param = json_decode($parent_param, true);
                    $specialist = $param['specialist'];
                    $date= $param['date'];

                    //get times

                    $response_dates = Http::get('https://admin.asknello.com/api/specialistscheduletime?specialization='.$specialist.'&date='.$date);

                    $resjson = $response_dates->json();


                    //use in array check if the user entered from the list of time

                    if(in_array($text, $resjson['available'])){

                        // if the input is correct

                        //$param = json_decode($parent_param, true);

                       
                

                        $new_param = [
                            "time" => $text,
                        ];


                        $textBody = "Kindly hold while I get available ".$specialist;

                        MessageHelper::sendTextMessage($token, $to, $textBody);


                        $next_step = "handle_doctor_consultation";
                        $parent_param = array_merge($param, $new_param);
            
                       

                        // $textBody = "*Nurse Chibuke Nwogu*";
                        // $url= "https://res.cloudinary.com/edifice-solutions/image/upload/v1675854461/Nurse_Frances-removebg-preview-min_dalmvt.png";

                        // $email = "frances.orakwue@famacare.com";
                        // $fee= 2000;


                        $response_doctors = Http::get('https://admin.asknello.com/api/specialistgetapi?specialization='.$specialist.'&date='.$date.'&time='.$text);

                        if($response_doctors["status"] == "success"){

                           // $doctor_list = [];

                            foreach($response_doctors['doctors'] as $doc){

                                $textBody = "*".$doc['title']. " ".$doc['firstname']."*";

                                $url = is_null($doc['picture']) ? 'https://admin.asknello.com/images/female_doc.png' : $doc['picture'];


                                $email = $doc['email'];
                                $fee= $doc['fee'];


                                //send this customer message based on the docotrs

                                MessageHelper::customDoctorInteractiveReplyWithImage($token, $to, $textBody, $url, $email, $fee);




                            }

                        
                        }

                        MessageHelper::updateNextStep($next_step, $to, $parent_param);


                       

                       
                    }

                    else{
                        // if the user entered a wrong input

                        $error = Botmessage::where('wa_id',$to)->update([
                            'error' => $error_count + 1,
                            'next_step' => "handle_time_consultation",
                        ]);




                        $textBody = "Invalid input, kindly chooose from the generated list";
                        $header = 'Choose Preferred Time';
                        $btnText = "Choose Time";


                        $specialization_time = [];

                        foreach($resjson['available']  as $doc_time){
                           
            
                            $specialization_time[] = [
                               "id" => $doc_time,
                                "title" => $doc_time,
                                "description" => ""
                            ];
                        } 


                        //send back the time 

                        if($error < 2){

                            MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $specialization_time, $btnText);

                        }
                       

                        
            


                    }
        





                    break;


                case "handle_doctor_consultation":

                    //handle doctor/nurse the user has choosen

                    Log::debug($payload);
                    Log::debug($text);


                    //check if doctor with that email exists


                    $check = $text == "Consult" ? $payload : $text;

                    

                    $userExist = User::where("email",$check)->exists();
                        Log::debug($userExist);

                    if($userExist){
                        //if the doctor /nurse exists

                        //send summary message with payment link
                        $param = json_decode($parent_param, true);

                        

                        $new_param = [
                            "doc_email" => $check,
                        ];

                        $user = User::where('email',$check)->first();

                        $fee = $user->fee;
                        $title = $user->title;
                        $firstname = $user->firstname;
                        $doc_uuid = $user->uuid;
                        $date = $param['date'];
                        $time = $param['time'];
                        



                        $textBody = "Proceed to make payment of N".$fee." to secure an online consultation with ".$title." ".$firstname." on ".$date." by ".$time." ";

                        
                        $btnText = "Make Payment";

                       

                        //draft online api.. to create a draft of the appointment

                        $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
                        $formattedDate = $parsedDate->format('d-m-Y');

                        $phone = User::where('email',$user_email)->value('phone');

                        $drafbooking = Http::withoutVerifying()->post('https://admin.asknello.com/api/draftbooking',[
                            
                            'date'=> $formattedDate,
                            'time' => $time,
                            'phone' => $phone,
                            'uuid' =>$doc_uuid,
                            'reason' => "Online Consultation",
                            'fee' => $fee
                        ]);


                        if($drafbooking['temp_id']){
                            //if the draft done, create a temp_id



                            $temp_id = $drafbooking['temp_id'];
                            $url = "https://mw.asknello.com/servicepay/?platform=whatsapp&agent_id=nello&user_code=nello&action=".$action."&temp_id=".$temp_id."&cost=".$fee."&email=".$user_email."&phone=".$to;

                            MessageHelper::sendBtnCallToActionMessage($token, $to, $textBody, $url, $btnText);

                            $next_step = "handle_payment_consultation";
                            $parent_param = array_merge($param, $new_param);
    
    
                            MessageHelper::updateNextStep($next_step, $to, $parent_param);


                        }


                      





                      
                    }

                    else{
                        //when the user make an error

                        //return the list of doctors and them to chose from it

                        $error = Botmessage::where('wa_id',$to)->update([
                            'error' => $error_count + 1,
                            'next_step' => "handle_doctor_consultation",
                        ]);

                        $param = json_decode($parent_param, true);
                            $specialist = $param['specialist'];
                            $date= $param['date'];
                            $time = $param['time'];


                            // call the doctors api

                            $textBody = "Invalid input, kindly click consult from the generated list";

                            MessageHelper::sendTextMessage($to, $token, $textBody);
                            Log::debug('passed');

                            $response_doctors = Http::get('https://admin.asknello.com/api/specialistgetapi?specialization='.$specialist.'&date='.$date.'&time='.$time);

                            if($response_doctors["status"] == "success"){
    
                               // $doctor_list = [];
    
                                foreach($response_doctors['doctors'] as $doc){
    
                                    $textBody = "*".$doc['title']. " ".$doc['firstname']."*";
    
                                    $url = is_null($doc['picture']) ? 'https://admin.asknello.com/images/female_doc.png' : $doc['picture'];
    
    
                                    $email = $doc['email'];
                                    $fee= $doc['fee'];
    
    
                                    //send this customer message based on the docotrs


                                    if($error < 2) {
                                        MessageHelper::customDoctorInteractiveReplyWithImage($token, $to, $textBody, $url, $email, $fee);

                                    }
                                    
    
    
    
    
                                }
    
                            
                            }







                    }

                    break;

//////////SPEAK TO AGENT///////////

                    case "handle_reach_agent":
                    
                        //code

                        Log::debug("true");


                        if($text == "WhatsApp Call" || $text == "Chat"){
                            //if correct

                            $textBody = "You've selected a ".$text." channel. A Nello Support Agent will contact you shortly.";

                            $update = Botmessage::where("wa_id",$to)->update([

                                'next_step' => "handle_services",
                                'parent_param' => null,
                                'error' => 0,
                                'action' => null,
            
                            ]);

                            MessageHelper::handleAgentEnd($token, $to, $textBody, $profileName, $text);



                        }

                        else{
                            $error = Botmessage::where('wa_id',$to)->update([
                                'error' => $error_count + 1,
                                'next_step' => "handle_reach_agent",
                            ]);


                            $textBody = "Invalid input, choose from below contact channel";


                            $reply_array = [

                                [
                                    "type" => "reply",
                                    "reply" => [
                                      "id" => "WhatsApp Call",
                                      "title" => "WhatsApp Call"
                                    ]
                                ],
        
                                [
                                    "type" => "reply",
                                    "reply" => [
                                      "id" => "Chat",
                                      "title" => "Chat"
                                    ]
                                ],
        
                            ];
        
                        
                            if($error < 2){
                            MessageHelper::sendInteractiveReply($token, $to, $textBody, $reply_array);
                            }


                        }

                        break;

                    case "handle_email_feedback":

                        //code validate email

                        if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
                            //if email is validated

                            // ask them to choose from the list of feedback

                            $textBody = "Thank you! Please choose the type of feedback you want to provide";
                            
        
                            

                            $textBody = "Thank you! Please choose the type of feedback you want to provide";
                    $header = 'Choose Feedback Type';
                    $btnText = "Feedback Type";


                    //get date from nelllo

                   
    
                    $resjson = ["General Enquiry","Product Delivery","Product Unavailable","Wrong Prescription","Product Mismatched","Side Effects","Delayed Appointment","Misconduct","Expired Drugs"];

                    // Log::debug($resjson);
                    $typess = [];

                    foreach($resjson  as $type){
                       

                        $typess[] = [
                           "id" => $type,
                            "title" => $type,
                            "description" => ""
                        ];
                    } 
                    


                    MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $typess, $btnText);
        
        
                            //next step will be to collect their lastname
                            //
        
                            //$param = json_decode($parent_param, true);
        
                            $parent_param = [
                                "email" => $text,
                            ];
        
                            $next_step = "handle_feedback_type";
                            // $parent_param = array_merge($param, $new_param);
        
                            MessageHelper::updateNextStep($next_step, $to, $parent_param);



                        }

                        else{
                            //when feedback email not validate email

                            $error = Botmessage::where('wa_id',$to)->update([
                                'error' => $error_count + 1,
                                'next_step' => "handle_email_feedback",
                            ]);
        
                            $textBody = "You've entered an invalid email format, Input a valid email address";
        
                            if($error < 2){
                                MessageHelper::sendTextMessage($token, $to, $textBody);
                            }
                        }

                        break;

                    case "handle_feedback_type":

                        //handle feedback type

                        $resjson = ["General Enquiry","Product Delivery","Product Unavailable","Wrong Prescription","Product Mismatched","Side Effects","Delayed Appointment","Misconduct","Expired Drugs"];

                        

                        $found = false;

                            // Iterate through the array
                            foreach ($resjson as $item) {
                                if (strcasecmp($item, $payload) === 0) {
                                    // Found a match
                                    $found = true;
                                    break;
                                }
                            }



                       // Log::debug($specialist);
                        //if($payload == "General Practitioner(GP)"){

                        if ($found) {

                            $param = json_decode($parent_param, true);
        
                            $new_param = [
                                "feedback_type" => $text,
                            ];

                            $textBody = "Thank you! Please type your feedback message. Type Cancel exit";

                            MessageHelper::sendTextMessage($token, $to, $textBody);

                            
        
                            $next_step = "handle_feedback_message";
                            $parent_param = array_merge($param, $new_param);

                         
        
                            MessageHelper::updateNextStep($next_step, $to, $parent_param);

                        }

                        else{

                            //if the user entered a text instead of clicking on the list

                            $error = Botmessage::where('wa_id',$to)->update([
                                'error' => $error_count + 1,
                                'next_step' => "handle_feedback_type",
                            ]);


                            $resjson = ["General Enquiry","Product Delivery","Product Unavailable","Wrong Prescription","Product Mismatched","Side Effects","Delayed Appointment","Misconduct","Expired Drugs"];

                            // Log::debug($resjson);
                            $typess = [];
        
                            foreach($resjson  as $type){
                               
        
                                $typess[] = [
                                   "id" => $type,
                                    "title" => $type,
                                    "description" => ""
                                ];
                            } 

                            $textBody = "Invalid input! Please choose the type of feedback you want to provide";
                            
        
                            

                    $header = 'Choose Feedback Type';
                    $btnText = "Feedback Type";
                            
        
                            if($error < 2){
                            MessageHelper::sendInteractiveListReply($token, $to, $textBody, $header, $typess, $btnText);
                            }
                        }

                        //code

                        break;


                    case "handle_feedback_message":

                        //code

                        $param = json_decode($parent_param, true);

                        $email = $param['email'];
                        $type = $param['feedback_type'];
                        $profile = $profileName;


                        MessageHelper::handleEndFeedback($token, $to,  $profile, $text, $email, $type);



                        break;

                
                default:
                    // code to be executed if expression does not match any case
            }
            


        }
            //return response()->json($nextstep);
        }

    }




    //reset all

    public function resetall(Request $request){

       // $userr= User::where('email',"edificeit@gmail.com")->delete();


        $user = Botmessage::where("wa_id",$request->to)->first();

         return response()->json($user);
        // $user = Botmessage::where("wa_id",$request->to)->update([
        //     'next_step' => null,
        //     "parent_param" => null,
        //     'error' => 0,
        //     // 'isAuth' => null,
        //     'action' => null,
        // ]);

        // return response()->json("all reset");
    }
}
