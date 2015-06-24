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
	$geocodingService = $this->app->make(\FindMeABike\Contracts\GeocodingService::class);
	$result = $geocodingService->geocode('Shedds Aquarium');
    return view('welcome');
});

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusById', 'FindMeABike\Http\Controllers\FindMeABike@findById');

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusByLocation', 'FindMeABike\Http\Controllers\FindMeABike@findByLocation');

AlexaRoute::intent('/alexa', 'GetMyLocationStatus', 'FindMeABike\Http\Controllers\FindMeABike@myLocationStatus');

AlexaRoute::intent('/alexa', 'SetMyLocationById', 'FindMeABike\Http\Controllers\FindMeABike@setLocationId');

AlexaRoute::intent('/alexa', 'SetMyLocationByLocation', 'FindMeABike\Http\Controllers\FindMeABike@setByLocation');