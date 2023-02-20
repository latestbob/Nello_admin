<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;



use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use App\ChatToken;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Embanqo Token for chatbot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Generate the embanqo token

        // $responsed = Http::withoutVerifying()->post('https://ost9cv8wr3.execute-api.us-east-2.amazonaws.com/qa/authentication',[
           
        //         "loginId" => "edidiong.bobson@asknello.com",
        //         "password" => "BOBson246"
              
        // ]);

        $responsed = Http::withoutVerifying()->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/authentication',[
           
            // "loginId" => "chukwunyere.ifionu@asknello.com",
            // "password" => "Asknello@2022"

            "loginId" => "Support@asknello.com",
            "password" => "Nello_Support"
          
    ]);
        
        
       

        $token = $responsed['data']['token'];
        //Log::debug($responsed['data']['token']);

        //

        $chatoken  = ChatToken::first();

        if(!$chatoken){
            $addtoken = new ChatToken;
            $addtoken->token = $token;
            $addtoken->save();

            Log::debug($token);
        }

        elseif($chatoken){
            $updatetoken  = ChatToken::where('token','!=',NULL)->update([
                'token' => $token
            ]);

            //Log::debug($token);

        }


        


    }
}
