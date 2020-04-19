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

    Route::get('/feedbacks', 'FeedbackController@index')->name('feedbacks');

    Route::get('/drugs', 'DrugController@drugs')->name('drugs');

    Route::match(['post', 'get'],'/drug/{uuid}/view', 'DrugController@drugView')->name('drug-view');

    Route::post('/drug/delete', 'DrugController@drugDelete')->name('drug-delete');

    Route::match(['post', 'get'],'/drug/add', 'DrugController@drugAdd')->name('drug-add');

    Route::get('/drugs-order', 'DrugController@drugOrders')->name('drugs-order');

    Route::get('/drugs-order/{uuid}/items', 'DrugController@drugOrderItems')->name('drugs-order-items');

    Route::post('/drugs-order/item/action', 'DrugController@drugOrderItemAction');

    Route::post('/drugs-order/item/add-prescription', 'DrugController@addPrescription');

    Route::get('/doctors', 'DoctorController@index')->name('doctors');

    Route::match(['post', 'get'],'/doctor/{uuid}/view', 'DoctorController@doctorView')->name('doctor-view');

//    Route::post('/doctor/{uuid}/update', 'DoctorController@updateDoctor')->name('doctor-update');

});
