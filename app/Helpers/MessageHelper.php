<?php

namespace App\Helpers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

use App\Botmessage;

class MessageHelper
{


    public static function getToken(){

        
        return "EAAFLCUXtZB7UBO82ECXI9FblJn8otqs138woG74l7LYZCW8ZCzNiCrr6Q384DEJV2wzZBE6nEQL60IW1VNZAZARXZCwZCHd8zidzsUF7cWIDFNGfOMfggSoZBMRPyvKJ088j1lsCSmVVBV0irvW3K0yzDvN5ifFY2PpRMNf2IpP8BpuOwb3DrkZANdbmCXFBlwUyrE";

    }

    

    public static function sendTextMessage($token, $to, $textBody)
    {
        // Your logic here
        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "text",
            
            "text" => [
                'body' => $textBody,
            ],
            
           
        ]);


    }


    //interactive reply message

    public static function sendInteractiveReply($token, $to, $textBody, $reply_array){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "interactive",
            "interactive" => [
                "type" => "button",
                "body" => [
                    "text" => $textBody,
                ],
                "footer" => [],
                "action" => [
                    "buttons" => $reply_array,
                ],

            ],

            
            
           
        ]);
    }


    // public static send template

    public static function sendTemplateMessage($token, $to){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "template",
            
            "template" => [
                'name' => 'nello_main',
                'language' => [
                    "code" => "en_Us",
                ],
                

            ],
           
           
        ]);
    }



    //update next steps and parent_param


    public static function updateNextStep($next_step, $to, $parent_param){



        $user = Botmessage::where('wa_id',$to)->update([
            "next_step" => $next_step,
            "parent_param" => $parent_param,
            "error" => 0,
            

        ]);

    }


    //handle error limit


    public static function handleErrorLimit($token, $to){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "text",
            
            "text" => [
                'body' => "You have exceeded your error Limit",
            ],
            
           
        ]);

        if($responsed){
            self::sendTemplateMessage($token, $to);
        }

    }


    //handle end feeedback

    public static function handleEndFeedback($token, $to, $profile, $text, $email, $type){

        // $feedback->name = $request->name;
        // $feedback->email = $request->email;
        // $feedback->type = $request->type;
        // $feedback->message = $request->message;
        // $feedback->priority = $request->priority;
        // $feedback->resolution_time = $request->resolution_time;
        // $feedback->dependencies = $request->dependencies;


        $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/customerfeedback',[
            "name" => $profile,
            "email" => $email,
            "type" => $type,
            "message" => $text,
            "priority" => 1,
            "resolution_time" => "24 hours",
            "dependencies" => "Service Delivery",
            
            
            
           
        ]);

        if($responsed['status'] == "success"){
            //send text messa

            $responsetwo = Http::withoutVerifying()->withHeaders([
                'Authorization'=> "Bearer ". $token,
                
            ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $to,
                "type" => "text",
                
                "text" => [
                    'body' => "Thank you for your feedback! We appreciate your input and will follow up if necessary.",
                ],
                
               
            ]);

            if($responsetwo){
                //send template

                self::sendTemplateMessage($token, $to);

                $update = Botmessage::where("wa_id",$to)->update([

                    'next_step' => "handle_services",
                    'parent_param' => null,
                    'error' => 0,
                    'action' => null,

                ]);
            }
        }
    }

    //handle speak to agent end

    public static function handleAgentEnd($token, $to, $textBody, $profile, $text){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "text",
            
            "text" => [
                'body' => $textBody,
            ],
            
           
        ]);

        if($responsed){
            //self::sendTemplateMessage($token, $to);

            $responsetwo = Http::withoutVerifying()->withHeaders([
                'Authorization'=> "Bearer ". $token,
                
            ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
               "messaging_product" => "whatsapp",
                    "to" => "2349023172944",
                    "type" => "contacts",
                    "contacts" => [
                        [
                            "name" => [
                                "formatted_name" => $profile,
                                "first_name" => $profile
                            ],
                            "phones" => [
                                [
                                    "phone" => $to,
                                    "type" => "whatsapp",
                                    "wa_id" => $to
                                ]
                            ]
                        ]
                    ]
                
               
            ]);

            if($responsetwo){
                $textbody= "New Support Request via ".$text." , contact name : ".$profile;
                $agent = "2349023172944";
               // self::sendTextMessage($token, $agent, $textbody);

                $responsethree = Http::withoutVerifying()->withHeaders([
                        'Authorization'=> "Bearer ". $token,
                        
                    ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
                        "messaging_product" => "whatsapp",
                        "recipient_type" => "individual",
                        "to" => $agent,
                        "type" => "text",
                        
                        "text" => [
                            'body' => $textbody,
                        ],
                        
                    
                    ]);


                    if($responsethree){
                        self::sendTemplateMessage($token, $to);
                    }


            }
        }

    }




    //handle button cta message

    public static function sendBtnCallToActionMessage($token, $to, $textBody, $url, $btnText){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "interactive",
            "interactive" => [
                "type" => "cta_url",
                "body" => [
                    "text" => $textBody,
                ],
                "footer" => [],
                "action" => [
                    "name" => "cta_url",

                    "parameters" => [
                        "display_text" => $btnText,
                        "url" => $url,
                    ],
                ],

            ],

            
            
           
        ]);
    }


    //interactive list_reply


    public static function sendInteractiveListReply($token, $to, $textBody, $header, $rows, $btnText){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[

            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "interactive",
            "interactive" => [
                "type" => "list",
                "header" => [
                    "type" => "text",
                    "text" => $header,
                ],
                "body" => [
                    "text" => $textBody,
                ],
                "footer" => [
                    "text" => ""
                ],
                "action" => [
                    "sections" => [
                        [
                            "title" => "Choose date",
                            "rows" => $rows
                        ]
                    ],
                    "button" => $btnText,
                ]
            ]
            
           
        ]);
    }


    //send customer reply message with image header

    public static function customDoctorInteractiveReplyWithImage($token, $to, $textBody, $url, $email, $fee){

        $responsed = Http::withoutVerifying()->withHeaders([
            'Authorization'=> "Bearer ". $token,
            
        ])->post('https://graph.facebook.com/v20.0/101097306144140/messages',[
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "interactive",
            "interactive" => [
                "type" => "button",
                "header" => [
                    "type" => "image",
                    "image" => [
                        "link" => $url,
                    ],
                ]
                ,
                "body" => [
                    "text" => $textBody,
                ],
                "footer" => [
                    "text" => "Fee - ".$fee,
                ],
                "action" => [
                    "buttons" => [
                        [
                            "type" => "reply",
                            "reply" => [
                              "id" => $email,
                              "title" => "Consult",
                            ]
                        ],
                    ]
                ],

            ],

            
            
           
        ]);
    }


}