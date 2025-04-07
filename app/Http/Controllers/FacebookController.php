<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Botmessage;

require_once app_path('Helpers/MessageHelper.php');

use App\Helpers\MessageHelper;
use Carbon\Carbon;


class FacebookController extends Controller
{
    //

    public function webhook(Request $request){
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === 'thisisatesttoken') {
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            } else {
                return response('Verification token mismatch', 403);
            }
        }

        // Handle the POST request for webhook events
        if ($request->isMethod('post')) {
           // $data = $request->all();

      

                 $response = $request->getContent();

            
                // Decode the JSON response to an associative array
                $data = json_decode($response, true);
                
                Log::debug($response);

                ///Bot details

                $whatsbussiness_id = $data['entry'][0]['id']; //business whatsapp id
                $phone_number_id = $data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                 $display_phone_number = $data['entry'][0]['changes'][0]['value']['metadata']['display_phone_number']; //bot number display number
                 

                 $token = MessageHelper::getToken();
                 //check if contact is present




                 if(isset($data['entry'][0]['changes'][0]['value']['contacts'])){
                    //
                    $profileName = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];

                    // Access the "wa_id for recipient phone number"
                    $waId = $data['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];


                    //check if user exists

                    $user = Botmessage::where("wa_id",$waId)->exists();
                    
                    //if the user haven't messaage the bot
                    if(!$user){

                        //add user 

                        $member = new Botmessage();
                        $member->wa_id = $waId;
                        $member->profile = $profileName;

                        $member->save();


                        

                        // send welcome messsage

                    }

                   
                    
                    
                    
                    $textType = $data['entry'][0]['changes'][0]['value']['messages'][0]['type'];


                    //change textType,, that me type of user action,, text or button or any other action

                    switch ($textType) {
                        case "text":
                            // code for text response

                            $textBody = $data['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
                            $payload = "";


                            $responsed = Http::withoutVerifying()->post('https://admin.asknello.com/api/bothook',[
                                "text" => $textBody,
                              
                                "to" => $waId,
                                "payload" => $payload,
                                   
                            ]);


                            
        
        
                            break;

                        case "button":


                            //code for button respnse

                                //when rensponse is button format or button is clicked
                        $textBody = $data['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
                        $payload = $data['entry'][0]['changes'][0]['value']['messages'][0]['button']['payload'];


                        $responsed = Http::withoutVerifying()->post('https://admin.asknello.com/api/bothook',[
                            "text" => $textBody,
                          
                            "to" => $waId,
                            "payload" => $payload,
                               
                        ]);
                            break;


                        //if user responsed to an interactive _quick reply button

                        case "interactive":

                            $type = $data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['type'];

                            if($type == "button_reply"){

                                $textBody = $data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['title'];
                                $payload = $data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'];
    
                                Log::debug($textBody);
    
                                $responsed = Http::withoutVerifying()->post('https://admin.asknello.com/api/bothook',[
                                    "text" => $textBody,
                                  
                                    "to" => $waId,
                                    "payload" => $payload,
                                       
                                ]);
    
    

                            }

                            else if($type == "list_reply"){
                                $textBody = $data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['title'];
                                $payload = $data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'];

                                Log::debug($textBody);
    
                                $responsed = Http::withoutVerifying()->post('https://admin.asknello.com/api/bothook',[
                                    "text" => $textBody,
                                  
                                    "to" => $waId,
                                    "payload" => $payload,
                                       
                                ]);

                            }

                          
                            break;
                        
                        default:
                            // code to be executed if expression does not match any case
                    }
                    

                 

                   

                   
                        

                    


                 }











                 else{
                    //bot action when contact is not present

                    //this is the bot response sent by the webhook to show if message has been rent by the user
                 }
         


    
       


       

       // return response()->json(['object' => $textBody]);
       return response('Event received', 200);

        }

        return response('Method not allowed', 405);
    }


    ///complete nello bot pass


    public function chatbotpass(Request $request){

        $token = MessageHelper::getToken();

        $getparams = Botmessage::where("wa_id",$request->phoneId)->first();

        $data = json_decode($getparams['parent_param'], true);

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email  = $data['email'];
        $phone = $data['phone'];
        $dob = $data['dob'];
        $gender = $data['gender'];
        $password = $request->password;
        $password_confirmation = $request->password;


        $dateTime = Carbon::createFromFormat('d/m/Y', $dob);
        $formattedDate = $dateTime->format('d-m-Y');

        

        //send api to create the new user
        $response = Http::withoutVerifying()->post('https://mw.asknello.com/api/auth/register',[
            'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'phone' => $phone,
        'gender' => $gender,
        'dob' => $formattedDate,
      
        'password' => $password,
        "password_confirmation" => $password,
    ]);


    //Log::debug($response);


        //Send a Welcome Message telling them their account is created

        if($response['token']){

            //send welcome template

            $textBody = "Hi $firstname,\n\nCongratulations! 🎉 You’re now signed up on Nello. You won't need to go through the previous steps again.\n\nWelcome to a simpler, smarter healthcare experience!";


            $updateAuth = Botmessage::where('wa_id',$request->phoneId)->update([
                'isAuth' => true,
                'quick_reply' => $email,
            ]);


            MessageHelper::sendTextMessage($token, $request->phoneId, $textBody);



            $action = Botmessage::where('wa_id',$request->phoneId)->value('action');

            

            switch ($action) {
                case 'Online Consultation':
                    # code...

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
                                // Log::debug($specialization);

                                // MessageHelper::sendInteractiveReply($token, $request->phoneId, $textBody, $specialization);
                                $responsed = Http::withoutVerifying()->withHeaders([
                                    'Authorization'=> "Bearer ". $token,
                                    
                                ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
                                    "messaging_product" => "whatsapp",
                                    "recipient_type" => "individual",
                                    "to" => $request->phoneId,
                                    "type" => "interactive",
                                    "interactive" => [
                                        "type" => "button",
                                        "body" => [
                                            "text" => $textBody,
                                        ],
                                        "footer" => [],
                                        "action" => [
                                            "buttons" => $formattedArray,
                                        ],
                        
                                    ],
                        
                                    
                                    
                                   
                                ]);

                                Log::debug($responsed);

                        //send Interactive message

                        //update next step

                        $updateAuth = Botmessage::where('wa_id',$request->phoneId)->update([
                            'next_step' => "handle_specialist_consultation",
                        ]);




                    break;

                case 'Medical Facility':

                    //code here

                    break;

                case  "Get Prescription":


                    //code here

                     break;
                
                default:
                    # code...
                    break;
            }


        return response()->json("success");


        }


       





        //return response()->json($data['firstname']);

    }


}
