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

    Route::get('/feedbacks', 'FeedbackController@index')->name('feedbacks')->middleware('auth.admin');

    Route::get('/appointments', 'AppointmentController@index')->name('appointments')->middleware('auth.admin');

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

    Route::post('/drugs-order/delivered', 'DrugController@drugOrderPickedUp')->middleware('auth.agent');

    Route::post('/drugs-order/item/add-prescription', 'DrugController@addPrescription')->name('add-prescription')->middleware('auth.admin.agent.doctor');

    Route::post('/drugs-order/item/add-doctors-prescription', 'DrugController@addDoctorsPrescription')->name('add-doctors-prescription')->middleware('auth.admin.agent.doctor');

    Route::get('/drug/coupons', 'CouponController@index')->name('coupons')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/coupons/add', 'CouponController@create')->name('coupons-add')->middleware('auth.admin');
    Route::match(['post', 'get'],'/drug/coupons/{coupon}/edit', 'CouponController@update')->name('coupons-edit')->middleware('auth.admin');
    Route::post('/drug/coupons/delete', 'CouponController@delete')->name('coupons-delete')->middleware('auth.admin');


    Route::get('/health-centers', 'HealthCenterController@index')->name('health-centers')->middleware('auth.admin');

    Route::post('/health-center/status', 'HealthCenterController@changeStatus')->name('health-center-status')->middleware('auth.admin');

    Route::match(['post', 'get'],'/health-center/{uuid}/view', 'HealthCenterController@viewHealthCenter')->name('health-center-view')->middleware('auth.admin');

    Route::match(['post', 'get'],'/health-center/add', 'HealthCenterController@addHealthCenter')->name('health-center-add')->middleware('auth.admin');

    Route::get('/doctors', 'DoctorController@index')->name('doctors')->middleware('auth.admin');

    Route::post('/doctor/status', 'DoctorController@changeStatus')->name('doctor-status')->middleware('auth.admin');

    //remove doctor

    Route::delete('/doctor/delete/{id}', 'DoctorController@deleteDoctor')->name('doctordelete')->middleware('auth.admin');

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
