<?php

namespace App\Helpers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

use App\Botmessage;

class MessageHelper
{
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
                'name' => 'nello_welcome',
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
}