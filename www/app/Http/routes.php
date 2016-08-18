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
    return view('welcome');
});


AlexaRoute::group(['middleware' => \Biker\Http\Middleware\AlexaAuth::class], function () {

    AlexaRoute::launch('/alexa', 'Biker\Http\Controllers\Biker@handleLaunch');

    AlexaRoute::sessionEnded('/alexa', 'Biker\Http\Controllers\Biker@handleSessionEnded');

    AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusById', 'Biker\Http\Controllers\Biker@findById');

    AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusByLocation', 'Biker\Http\Controllers\Biker@findByLocation');

    AlexaRoute::intent('/alexa', 'GetMyLocationStatus', 'Biker\Http\Controllers\Biker@myLocationStatus');

    AlexaRoute::intent('/alexa', 'SetMyLocationById', 'Biker\Http\Controllers\Biker@setLocationId');

    AlexaRoute::intent('/alexa', 'SetMyLocationByLocation', 'Biker\Http\Controllers\Biker@setByLocation');

    AlexaRoute::intent('/alexa', 'GetMyDeviceCode', 'Biker\Http\Controllers\Biker@getDeviceCode');

    AlexaRoute::intent('/alexa', 'ResetMyDeviceCode', 'Biker\Http\Controllers\Biker@resetDeviceCode');

    AlexaRoute::intent('/alexa', 'ResetAccount', 'Biker\Http\Controllers\Biker@detachAccount');

    AlexaRoute::intent('/alexa', 'ConfirmDetachAccount', 'Biker\Http\Controllers\Biker@confirmDetachAccount');

});


Route::get('setup', 'Auth\AuthController@getSetup');

Route::auth();

Route::get('/home', 'HomeController@index');

