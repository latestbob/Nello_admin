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

Route::get('/onlinedoctors','ChatBotController@getonlinedoctors');


//DraftOnlineBooking API doctor

Route::post('/draftbooking','ChatBotController@draftonlinebooking');


//complete Online Booking

Route::post('/completeonlinebook','ChatBotController@completeOnlineBooking');


//getstates

Route::get('/getstates','ChatBotController@getStates');

//get Locations
Route::get('/getlocation','ChatBotController@getLocations');


//get Facilities

Route::get('/getfacilities','ChatBotController@getFacilities');


//checkavailability

Route::get('/checkavailability','ChatBotController@checkavailability');

// draft Facility visit

Route::post('/facilitybook','ChatBotController@draftfacilitybooking');


//complete facility visit

Route::post('/completefacilitybooking','ChatBotController@completefacilityvisit');


//Embanqo Webhook Url

Route::post('/webhooksend','ChatBotController@webhook');

Route::post('/webhook','ChatBotController@webhookreceive');


//Production Webhook Url

Route::post('/webhookprod','ChatBotController@webhookproduction');



Route::post('/famacarepassword','CustomerController@activitiesonpassword');


// OWC cONTROLLER
//create OWC appointment

Route::post('/owc/appointment','OwcController@create');


//Check appointment is booked

Route::get('/checkappointment','OwcController@checkappointment');


//check available time

Route::get('/checktime','OwcController@checktime');

//Embanqo pass post

Route::post('/chatbotpass','ChatBotController@chatbotpass');


//real location embanqo facility

Route::get('/realocation', 'ChatBotController@realocation');

//get date array for owcappointment.com



Route::get('/getdatearray','OwcController@getdatearray');

Route::get('/getowcdatechatbot','OwcController@getowcdatechatbot');


//get time owcEmbanqo chatbot

Route::get("/checktimebot","OwcController@checktimebot");







//Get OWC doctors by specializtion

Route::get('/owc/doctors','OwcController@getDoctorSpec');

Route::get("/owcgetmostdate","OwcController@getmostdate");


// Nello Webstite frontend get doctors calendar days

Route::get("/nello_doctors_days/{uuid}","DoctorController@nellodoctorscalendardays");

//Nello website frontend get facility visit calendar days

Route::get("/nello_facility_days/{uuid}",'HealthCenterController@nellofacilitycalendardays');


//get nello website doctors appointment time and booked time

Route::get('/doctor_appointment_time','DoctorController@getdoctorappointmenttime');


//get nello website medical appointment time and booked time

Route::get("/medical_appointment_time",'HealthCenterController@getmedicalappointmenttime');


//Delete Nello Appointment

Route::delete("/delete/appointment","AppointmentController@deleteappointments");

Route::delete("/users/{email}","DoctorController@deleteUsers");


//Get OWC block calander date

Route::get("/owcblocktime","OwcController@getblockedtime");


///SPECIALIST SCHEDULE GET DATES


Route::get("/specialistschedule","AppointmentController@specialscheduleadatesapi");

//Specialist schedule gets time based on date and specialization

Route::get("/specialistscheduletime","AppointmentController@specialistscheduletime");


//get specialist based on date, specialization and time

Route::get("/specialistgetapi",'AppointmentController@specialistgetapi');



//health center facility spec get dates api

Route::get("/healthcenterdatesapi","AppointmentController@healthcenterdatesapi");

//health center facility spec get time associated to a date api

Route::get("/healthcenterdatetimeapi","AppointmentController@healthcenterdatetimeapi");



/////////////nello website dates time  doctor / facility appointment/////////////////

//Route::get("/nellodocdates","AppointmentController@getdatesdoctornellowebsite");

Route::get("/nellodoctordates","AppointmentController@getwebsitedoctordate");

//get time for a unique specialist

Route::get("/nellodoctortimes","AppointmentController@getwebsitedoctortime");


//get nello website facility date

Route::get("/nellofacilitydates","AppointmentController@getwebsitefacilitydate");

//get nello website facility time
Route::get("/nellofacilitytimes","AppointmentController@getnellowebsitefacilitytime");



////////////////////////////////////////////////////////////////////////

//Route::put("/update/beauty","DrugController@updatebeauty");

Route::get("/skinns","DrugController@skinns");

//get specialization for each unique healthcenter

Route::get("/healthcenter/spec","HealthCenterController@getspecunique");


//get all healthcenters for nellofrontend

Route::get("/nellogetmedcenters","HealthCenterController@getnellomedcenters");

//get facility by service


Route::get("/nellomedcentername","HealthCenterController@nellocentersbyspec");


//get location nellofrontend

Route::get("/facilitylocation","LocationController@getfacilitylocationss");


//Nello medical report get drug array

Route::get("/nelloavailabledrugs","DrugController@getavailabledrugs");


//Nello medical report get appoint by reference

Route::get("nellogetappointment/{ref}","AppointmentController@getappointmentref");


//Nello medical records post consultation records

Route::post("/nellomedicalrecords","AppointmentController@createnewrecord");


//Get all medical Records

Route::get("/medicalrecords","AppointmentController@getallmedicalrecords");

//get all specialization and caretype nello frontend

Route::get("/getsearchinput","AppointmentController@getsearchinput");

Route::get("/getdoctorcenter","AppointmentController@getdoctorcenter"); //for nello frontend


// nello interest form

Route::post("/join","BusinessController@interest");
