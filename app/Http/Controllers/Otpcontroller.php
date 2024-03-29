<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Otp;
use Illuminate\Support\Facades\Validator;

class Otpcontroller extends Controller
{
    //

    public function generateotp(Request $request){

$validator = Validator::make($request->all(), [
            'phone' => 'required|digits:11'
        ]);


if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ],400);
        }

        $otp = new Otp;
    $otp_token = $otp->generate($request->phone, 6, 10);
    
    return response()->json($otp_token);
    }


    //validate otp

    public function validateotp(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
                'code' => 'required|string'
        ]);


if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        $code = $request->code;
        $otp = new Otp;
    $valid = $otp->validate($request->phone, $code);
    
    return response()->json($valid);
    }

}