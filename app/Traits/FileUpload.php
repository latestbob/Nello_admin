<?php

namespace App\Traits;


use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

trait FileUpload {
    public function uploadFile(Request $request, string $key)
    {
        if ($request->hasFile($key)) {
            $uploadedFileUrl = Cloudinary::upload($request->file($key)->getRealPath())->getSecurePath();
            return $uploadedFileUrl;
        }
        return null;
    }
}
