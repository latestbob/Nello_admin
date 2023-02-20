<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();



Route::prefix('/')->middleware(['auth', 'auth.allowed'])->group(function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('/account','DashboardController@myaccount')->name('myaccount')->middleware('auth.admin');

    Route::get('/feedbacks', 'FeedbackController@index')->name('feedbacks')->middleware('auth.admin');

    Route::get('/appointments', 'AppointmentController@index')->name('appointments')->middleware('auth.admin');

    //appointment reschedule

    Route::get("/appointment/reschedule/{id}","AppointmentController@appointmentreschedule")->name("appointmentreschedule")->middleware("auth.admin.famacare");

    Route::get('/drugs', 'DrugController@drugs')->name('drugs')->middleware('auth.admin');

    Route::match(['post', 'get'],'/drug/{uuid}/view', 'DrugController@drugView')->name('drug-view')->middleware('auth.admin');

    Route::post('/drug/delete', 'DrugController@drugDelete')->name('drug-delete')->middleware('auth.admin');

    Route::post('/drug/status', 'DrugController@drugStatus')->name('drug-status')->middleware('auth.admin');

    Route::match(['post', 'get'],'/drug/add', 'DrugController@drugAdd')->name('drug-add')->middleware('auth.admin');
    Route::get('/drug/categories', 'DrugController@drugCategories')->name('drug-categories')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/categories/add', 'DrugController@drugCategoryAdd')->name('drug-categories-add')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/categories/{id}/edit', 'DrugController@drugCategoryUpdate')->name('drug-categories-edit')->middleware('auth.admin');
    Route::post('/drug/categories/delete', 'DrugController@drugCategoryDelete')->name('drug-categories-delete')->middleware('auth.admin');

    Route::get('/drugs-import', 'DrugController@drugImport')->name('drugs-import')->middleware('auth.admin');

    Route::get('/drugs-order', 'DrugController@drugOrders')->name('drugs-order')->middleware('auth.admin.agent.doctor');

    Route::get('/drugs-order/{uuid}/items', 'DrugController@drugOrderItems')->name('drugs-order-items')->middleware('auth.admin.agent.doctor');

    Route::post('/drugs-order/item/action', 'DrugController@drugOrderItemAction')->middleware('auth.admin');

    Route::post('/drugs-order/item/ready', 'DrugController@drugOrderItemReady')->middleware('auth.agent');

    Route::post('/drugs-order/delivered/{ref}', 'DrugController@drugOrderPickedUp')->name("delivered_drugs")->middleware('auth.admin');

    Route::post('/drugs-order/item/add-prescription', 'DrugController@addPrescription')->name('add-prescription')->middleware('auth.admin.agent.doctor');

    Route::post('/drugs-order/item/add-doctors-prescription', 'DrugController@addDoctorsPrescription')->name('add-doctors-prescription')->middleware('auth.admin.agent.doctor');

    Route::get('/drug/coupons', 'CouponController@index')->name('coupons')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/coupons/add', 'CouponController@create')->name('coupons-add')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/coupons/{coupon}/edit', 'CouponController@update')->name('coupons-edit')->middleware('auth.admin');
    Route::post('/drug/coupons/delete', 'CouponController@delete')->name('coupons-delete')->middleware('auth.admin');


    Route::get('/health-centers', 'HealthCenterController@index')->name('health-centers')->middleware('auth.admin');

    Route::post('/health-center/status', 'HealthCenterController@changeStatus')->name('health-center-status')->middleware('auth.admin');

    Route::match(['post', 'get'],'/health-center/{uuid}/view', 'HealthCenterController@viewHealthCenter')->name('health-center-view')->middleware('auth.admin');

    Route::get('/health-center/{uuid}/spec-schedule','HealthCenterController@specschedule')->name('health-center-specschedule')->middleware('auth.admin');

    // Add specialization

    Route::post('/health-center/{uuid}/addspec','HealthCenterController@addspec')->name('health-center-addspec')->middleware('auth.admin');

    //Remove Specialization

    Route::delete('/health-center/spec/{id}','HealthCenterController@deletespec')->name('health-center-delete-spec')->middleware('auth.admin');

    //add medical center schedule

    Route::post('/health-center/{uuid}/addschedule','HealthCenterController@addschedule')->name('health-center-addschedule')->middleware('auth.admin');

    //delete medical center schedule

    Route::delete('/health-center/{id}/schedule','HealthCenterController@deleteschedule')->name('health-center-deleteschedule')->middleware('auth.admin');


    Route::match(['post', 'get'],'/health-center/add', 'HealthCenterController@addHealthCenter')->name('health-center-add')->middleware('auth.admin');

    Route::get('/doctors', 'DoctorController@index')->name('doctors')->middleware('auth.admin');

    Route::post('/doctor/status', 'DoctorController@changeStatus')->name('doctor-status')->middleware('auth.admin');

    //remove doctor

    Route::delete('/doctor/delete/{id}', 'DoctorController@deleteDoctor')->name('doctordelete')->middleware('auth.admin');


    //doctor schedule page

    Route::get('/doctor/{uuid}/schedule','DoctorController@doctorschedule')->name('doctorschedule')->middleware('auth.admin');

    //doctor schedule add

    Route::post('/doctor/{uuid}/schedule','DoctorController@doctorscheduleadd')->name('doctor-schedule-add')->middleware('auth.admin');

    //doctor schedule remove

    Route::delete('/doctor/schedule/{id}','DoctorController@doctorscheduledelete')->name('doctor-schedule-delete')->middleware('auth.admin');

    Route::match(['post', 'get'],'/doctor/{uuid}/view', 'DoctorController@viewDoctor')->name('doctor-view')->middleware('auth.admin');

    Route::match(['post', 'get'],'/doctor/add', 'DoctorController@addDoctor')->name('doctor-add')->middleware('auth.admin');

    Route::get('/doctor/messages', 'DoctorMessageController@index')->name('doctor-messages')->middleware('auth.doctor.admin');

    Route::get('/locations', 'LocationController@index')->name('locations')->middleware('auth.admin');

    Route::match(['post', 'get'],'/location/add', 'LocationController@addLocation')->name('location-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/location/{uuid}/view', 'LocationController@viewLocation')->name('location-view')->middleware('auth.admin');

    Route::post('/location/delete', 'LocationController@deleteLocation')->name('location-delete')->middleware('auth.admin');

    Route::match(['post', 'get'],'/point/rule', 'CustomerPointController@index')->name('point-rule')->middleware('auth.admin');

    Route::match(['post', 'get'],'/prescription/fee', 'PrescriptionFeeController@index')->name('prescription-fee')->middleware('auth.admin');

    Route::get('/health-tips', 'HealthTipController@index')->name('health-tips')->middleware('auth.admin');

    Route::match(['post', 'get'],'/health-tip/add', 'HealthTipController@addTip')->name('health-tip-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/health-tip/{uuid}/view', 'HealthTipController@viewTip')->name('health-tip-view')->middleware('auth.admin');

    Route::get('/pharmacies', 'PharmaciesController@index')->name('pharmacies')->middleware('auth.admin');

    Route::match(['post', 'get'],'/pharmacy/add', 'PharmaciesController@addPharmacy')->name('pharmacy-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/pharmacy/{uuid}/view', 'PharmaciesController@viewPharmacy')->name('pharmacy-view')->middleware('auth.admin');

    Route::post('/pharmacy/delete', 'PharmaciesController@deletePharmacy')->name('pharmacy-delete')->middleware('auth.admin');

    Route::get('/pharmacy/agents', 'PharmaciesController@viewAgents')->name('pharmacy-agents')->middleware('auth.admin');

    Route::post('/pharmacy/agent/delete', 'PharmaciesController@deleteAgent')->name('pharmacy-agent-delete')->middleware('auth.admin');

    Route::get('/riders', 'RiderController@index')->name('riders')->middleware('auth.admin');

    Route::match(['post', 'get'],'/rider/add', 'RiderController@addRider')->name('rider-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/rider/{uuid}/view', 'RiderController@viewRider')->name('rider-view')->middleware('auth.admin');

    Route::post('/rider/delete', 'RiderController@deleteRider')->name('rider-delete')->middleware('auth.admin');

    Route::get('/customers', 'CustomerController@index')->name('customers')->middleware('auth.admin');

    Route::match(['post', 'get'],'/customer/{uuid}/view', 'CustomerController@viewCustomer')->name('customer-view')->middleware('auth.admin');

    Route::post('/customer/make-agent', 'CustomerController@makeAgent')->name('customer-make-agent')->middleware('auth.admin');

});

Route::get('/doctors-prescription/{uuid}', 'DoctorsPrescriptionController@index')->name('doctors-prescription');


Route::match(['post', 'get'],'/transactions', 'TransactionController@index')->name('transaction-view')->middleware('auth.admin');


Route::get('/passwordactivity','CustomerController@activities')->name('password-activity')->middleware('auth.admin');

//appointment vistation route

Route::get('/visitation/{ref}','AppointmentController@visitation');


Route::get('/owcappointment','OwcController@getappointment')->middleware('auth')->name('owcappointment');


//delete OWCappoint


Route::delete("/owcdelete/{id}",'OwcController@deleteappointment')->middleware("auth.admin")->name("deleteowcappointment");

//download 

Route::get('/booking/{ref}','OwcController@download')->name('bookingref');


//OWC DASHBOARD

Route::get('/owc-dashboard','OwcController@dashboard')->middleware('auth.owc')->name('owcadmin');



///FAMACARE DASHBOARD

Route::get('/famacare-dashboard','FamacareController@dashboard')->middleware("auth.famacare")->name("famacareadmin");



//deactivate customer account

Route::put('/deactiveaccount/{id}','CustomerController@deactivateaccount')->name('deactivateaccount')->middleware('auth.admin');

//Activate user account
Route::put('/activeaccount/{id}','CustomerController@activateaccount')->name('activateaccount')->middleware('auth.admin');


//Update order delivery status


Route::put('/delivered/{id}','DrugController@delivered')->name('delivered')->middleware('auth.admin');


//Nello Embanqo passsworod

Route::get('/embanqo/password','ChatBotController@password')->name('passwordroute');


//post of NEllo emBANQO password



//Admin appointment status update
Route::put('/appointment/status/{id}','AppointmentController@updatestatus')->name('appointmentupdate');

//update to pending

Route::put('/appointment/pending/{id}','AppointmentController@updatepending')->name('appointmentpending');


//OWC medical schedule

Route::get('/calendar','OwcController@medicalcalendar')->middleware('auth.owc')->name('owcmedicalcalender');

// Owc Medical schedule create

Route::post("/calendarpost",'OwcController@medicalcalendarpost')->middleware("auth.owc")->name("owcmedicalcalendarpost");

//OWC APPOINTMENT MANAGE TIME

Route::get("/caleandarpostime/{id}",'OwcController@owcmanagetime')->middleware("auth.owc")->name("owcascheduletime");

//OWC APPOINTMENT MANAGE TIME POST

Route::post("/calendarposttimepost",'OwcController@owcmanagetimepost')->middleware("auth.owc")->name("owcscheduletimepost");

//OWC APPOINTMENT MANAGE TIME DELETE

Route::delete("/calendarpostimedelete/{id}",'OwcController@owcmanagetimedelete')->middleware("auth.owc")->name("owcscheduletimedelete");

//Delete date

Route::delete('/calendar/{id}','OwcController@deletecalendardate')->middleware("auth.owc")->name("deletecalendardate");

//Delete Entire Calendar

Route::post('/delete/owcalendar','OwcController@calendardelete')->middleware("auth.owc")->name("calendardelete");

//reschedule neello admin appointment
Route::put("/reschedule/admin","AppointmentController@rescheduleadmin")->middleware("auth.admin.famacare")->name("rescheduleadmin");



//////////////////////////////////////FAMACARE DASHBOARD ROUTES////////////////////////////////////

Route::get("/famacare/appointment",'FamacareController@appointment')->middleware("auth.famacare")->name("famacareappointment");

//physical appointment

Route::get("/famacare/physical","FamacareController@physicalappointment")->middleware("auth.famacare")->name("famacarephysicalappointment");

//specialist schedule famacare page

Route::get("/famacare/specialist","FamacareController@specialist")->middleware("auth.famacare")->name("famacarespecialist");


/////////////////////////////////////////////////////////////////////////////////////////////////


//Specialist Calendar

Route::get("/specialist/calendar/{id}","AppointmentController@specialistcalendar")->middleware("auth.admin.famacare")->name("specialistcalendar");

//specialist calendar post

Route::post("/specialistcalendarpost","AppointmentController@specialistcalendarpost")->middleware("auth.admin.famacare")->name("specialistcalendarpost");

//Delete Specialist Schedule

Route::delete("/specialistcalendardelete/{id}","AppointmentController@deletespecialistcalender")->middleware("auth.admin.famacare")->name("deletespecialistcalendar");

//manage time

Route::get("/specialistcalendertime/{id}","AppointmentController@specialistcalendertime")->middleware("auth.admin.famacare")->name("specialistcalendertime");

//manage time post

Route::post("/specialistcalendertimepost","AppointmentController@specialistcalendertimepost")->middleware("auth.admin.famacare")->name("specialistcalendertimepost");

//remove time

Route::delete("/specialistcalendertimedelete/{id}","AppointmentController@specialistcalendertimedelete")->middleware("auth.admin.famacare")->name("specialistcalendertimedelete");

//delete dates and time for a particular specialist

Route::delete("/deletedoctorschedule/{uuid}","AppointmentController@deletespecificspecialistschedules")->middleware("auth.admin.famacare")->name("deletespecificspecialistschedules");
