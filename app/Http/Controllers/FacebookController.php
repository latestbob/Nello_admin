<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Botmessage;

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

                 $token = 'EAAN229vKZB50BO5ZC9tHwSZAhaUrZC9FKiQ8UNbYZAb5PYsOECfIi5zMK9rAMZCpkX4wt7mPbxoEdOro0G83ZC1yikNX7eQFYiKURQtrP5YSv4ogCLZBaVKUj19dbc26ZAuLMPLv1dvZAR40uACLuknh1iAY3Iz2xnFu81mPqCuMwHVqPnSTmfafQfcnu5JBnwN8PgvIX16mtJ1R0SIZCaZAyqgZD';
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


}
