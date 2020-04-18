<?php

namespace App\Traits;


use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;

trait FileUpload {
    public function uploadFile(Request $request, string $key) 
    {
        if ($request->hasFile($key)) {
            Cloudder::upload($request->file($key));
            $response = Cloudder::getResult();
            return $response['url'];
        }
        return null;
    }
}
