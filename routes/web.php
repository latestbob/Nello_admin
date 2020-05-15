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

Route::prefix('/')->middleware('auth')->group(function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('/feedbacks', 'FeedbackController@index')->name('feedbacks')->middleware('auth.admin');

    Route::get('/drugs', 'DrugController@drugs')->name('drugs')->middleware('auth.admin');

    Route::match(['post', 'get'],'/drug/{uuid}/view', 'DrugController@drugView')->name('drug-view')->middleware('auth.admin');

    Route::post('/drug/delete', 'DrugController@drugDelete')->name('drug-delete')->middleware('auth.admin');

    Route::match(['post', 'get'],'/drug/add', 'DrugController@drugAdd')->name('drug-add')->middleware('auth.admin');

    Route::get('/drugs-order', 'DrugController@drugOrders')->name('drugs-order');

    Route::get('/drugs-order/{uuid}/items', 'DrugController@drugOrderItems')->name('drugs-order-items');

    Route::post('/drugs-order/item/action', 'DrugController@drugOrderItemAction');

    Route::post('/drugs-order/item/add-prescription', 'DrugController@addPrescription');

    Route::get('/doctors', 'DoctorController@index')->name('doctors')->middleware('auth.admin');

    Route::match(['post', 'get'],'/doctor/{uuid}/view', 'DoctorController@doctorView')->name('doctor-view')->middleware('auth.admin');

    Route::get('/locations', 'LocationController@index')->name('locations')->middleware('auth.admin');

    Route::match(['post', 'get'],'/location/add', 'LocationController@addLocation')->name('location-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/location/{uuid}/view', 'LocationController@viewLocation')->name('location-view')->middleware('auth.admin');

    Route::post('/location/delete', 'LocationController@locationDelete')->name('location-delete')->middleware('auth.admin');

    Route::match(['post', 'get'],'/point/rule', 'CustomerPointController@index')->name('point-rule')->middleware('auth.admin');

    Route::get('/agents', 'AdminController@viewAgents')->name('agents')->middleware('auth.admin');

    Route::match(['post', 'get'],'/agent/add', 'AdminController@addAgent')->name('agent-add')->middleware('auth.admin');

    Route::match(['post', 'get'],'/agent/{uuid}/view', 'AdminController@viewAgent')->name('agent-view')->middleware('auth.admin');

});
