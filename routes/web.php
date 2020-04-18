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

    Route::get('/drugs-order', 'DrugOrderController@index')->name('drugs-order');

    Route::get('/drugs-order/items/{uuid}', 'DrugOrderController@orderItems')->name('drugs-order-items');

    Route::post('/drugs-order/item/action', 'DrugOrderController@drugOrderItemAction');

    Route::post('/drugs-order/item/add-prescription', 'DrugOrderController@addPrescription');

    Route::get('/doctors', 'DoctorController@index')->name('doctors');

    Route::match(['post', 'get'],'/doctor/{uuid}/view', 'DoctorController@editDoctor')->name('doctor-view');

//    Route::post('/doctor/{uuid}/update', 'DoctorController@updateDoctor')->name('doctor-update');

});
