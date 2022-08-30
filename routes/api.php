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


//Embanqo controller here

//OnlineDoctorList api

Route::get('/onlinedoctors','EmbanqoController@getonlinedoctors');


//DraftOnlineBooking API doctor

Route::post('/draftbooking','EmbanqoController@draftonlinebooking');


//complete Online Booking

Route::post('/completeonlinebook','EmbanqoController@completeOnlineBooking');


//getstates

Route::get('/getstates','EmbanqoController@getStates');

//get Locations
Route::get('/getlocation','EmbanqoController@getLocations');


//get Facilities

Route::get('/getfacilities','EmbanqoController@getFacilities');


//checkavailability

Route::get('/checkavailability','EmbanqoController@checkavailability');

// draft Facility visit

Route::post('/facilitybook','EmbanqoController@draftfacilitybooking');


//complete facility visit

Route::post('/completefacilitybooking','EmbanqoController@completefacilityvisit');


//Embanqo Webhook Url

Route::post('/webhooksend','EmbanqoController@webhook');

Route::post('/webhook','EmbanqoController@webhookreceive');



Route::post('/famacarepassword','CustomerController@activitiesonpassword');


