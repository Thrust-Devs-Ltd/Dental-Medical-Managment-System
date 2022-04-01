<?php

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


Route::group(['middleware' => ['auth']], function () {
    Route::prefix('doctor')->group(function () {
        Route::get('/', 'DoctorController@index');
    });

    Route::resource('doctor-appointments', 'AppointmentsController');
    Route::post('appointment-status', 'AppointmentsController@updateAppointmentStatus');
    Route::resource('claims', 'DoctorClaimController');
});
