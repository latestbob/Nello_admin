<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('drugs/import', 'Api\DrugController@import')->name('drug-import');
Route::post('doctors/import', 'Api\DoctorController@import')->name('doctor-import');

Route::post('/gen/otp','Otpcontroller@generateotp');

Route::post('/validate/otp','Otpcontroller@validateotp');