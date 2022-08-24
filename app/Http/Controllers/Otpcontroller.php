<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Otp;


class Otpcontroller extends Controller
{
    //

    public function generateotp(){
        $otp = new Otp;
    $otp_token = $otp->generate('nello', 6, 3);
    
    return response()->json($otp_token);
    }

    //validate otp

    public function validateotp(Request $request){
        $code = $request->code;
        $otp = new Otp;
    $valid = $otp->validate('nello', $code);
    
    return response()->json($valid);
    }


}
