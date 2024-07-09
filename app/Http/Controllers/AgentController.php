<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;


class AgentController extends Controller
{
    //agent dashbaord

    public function dashboard(){
        return view("agentdashoard");
    }


    //test shortner

    public function test(Request $request){

        
        $responsed = Http::withoutVerifying()->withHeaders([
            //  'Content-Type'=> 'application/x-www-form-urlencoded',
            'X-RapidAPI-Key' => '153aad5a4emshc3de77aedbeee81p164b17jsn305dd7248559',
            'X-RapidAPI-Host' => 'url-shortener-service.p.rapidapi.com'
            // Back to menu
        ])->post('https://url-shortener-service.p.rapidapi.com/shorten',[
           
            'url' => 'https://mw.asknello.com/servicepay/?platform=whatsapp&agent_id=253&user_code=2349047808865&action=book.online.consultation&temp_id=49916159&cost=3000&email=bobsonedidiong@yahoo.com',
            
        ]);

        if($responsed){
           return $responsed['result_url'];
        }
    }
}
