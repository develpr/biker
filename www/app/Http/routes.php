<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

AlexaRoute::launch('/alexa', 'FindMeABike\Http\Controllers\FindMeABike@handleLaunch');

AlexaRoute::sessionEnded('/alexa', 'FindMeABike\Http\Controllers\FindMeABike@handleSessionEnded');

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusById', 'FindMeABike\Http\Controllers\FindMeABike@findById');

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusByLocation', 'FindMeABike\Http\Controllers\FindMeABike@findByLocation');

AlexaRoute::intent('/alexa', 'GetMyLocationStatus', 'FindMeABike\Http\Controllers\FindMeABike@myLocationStatus');

AlexaRoute::intent('/alexa', 'SetMyLocationById', 'FindMeABike\Http\Controllers\FindMeABike@setLocationId');

AlexaRoute::intent('/alexa', 'SetMyLocationByLocation', 'FindMeABike\Http\Controllers\FindMeABike@setByLocation');

AlexaRoute::intent('/alexa', 'GetMyDeviceCode', 'FindMeABike\Http\Controllers\FindMeABike@getDeviceCode');

AlexaRoute::intent('/alexa', 'ResetMyDeviceCode', 'FindMeABike\Http\Controllers\FindMeABike@resetDeviceCode');

AlexaRoute::intent('/alexa', 'ResetAccount', 'FindMeABike\Http\Controllers\FindMeABike@detachAccount');
AlexaRoute::intent('/alexa', 'ConfirmDetachAccount', 'FindMeABike\Http\Controllers\FindMeABike@confirmDetachAccount');





/**
 *				AUTH
 */
// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
