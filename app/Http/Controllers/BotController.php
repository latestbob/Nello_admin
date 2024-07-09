<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Botmessage;
use App\Models\User;


require_once app_path('Helpers/MessageHelper.php');

use App\Helpers\MessageHelper;


class BotController extends Controller
{
    //

    public function bothook(Request $request){
        if($request->isMethod('post')){
            
            $token = 'EAAN229vKZB50BO5ZC9tHwSZAhaUrZC9FKiQ8UNbYZAb5PYsOECfIi5zMK9rAMZCpkX4wt7mPbxoEdOro0G83ZC1yikNX7eQFYiKURQtrP5YSv4ogCLZBaVKUj19dbc26ZAuLMPLv1dvZAR40uACLuknh1iAY3Iz2xnFu81mPqCuMwHVqPnSTmfafQfcnu5JBnwN8PgvIX16mtJ1R0SIZCaZAyqgZD';

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

                    $auth = Botmessage::where('wa_id',$to)->value("isAuth");

                    

                    switch ($text) {


                        case "Online Consultation":
                            // code... when the user is not authenticated

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

                            }
                            
                    




                            break;


                        case 'Medical Facility':
                                # code... medical facility action
                                break;

                        case 'Get Prescription':
                            # code... prescription action
                            break;
                        
                        default:
                            # code...
                            break;
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


                if (preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d\d$/", $dob)) {
                    // The date of birth is in the correct format (dd/mm/yyyy)
                } else {


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


                
                default:
                    // code to be executed if expression does not match any case
            }
            


        }
            //return response()->json($nextstep);
        }

    }




    //reset all

    public function resetall(Request $request){

        $user = Botmessage::where("wa_id",$request->to)->first();

        return response()->json($user);
        // $user = Botmessage::where("wa_id",$request->to)->update([
        //     'next_step' => null,
        //     "parent_param" => null,
        //     'error' => 0,
        // ]);

        // return response()->json("all reset");
    }
}
