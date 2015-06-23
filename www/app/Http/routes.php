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
	/** @var \FindMeABike\Services\Data\DivvyImporter $importer */
	$importer = $this->app->make('\FindMeABike\Services\Data\DivvyImporter');
	$importer->refreshData();
    return view('welcome');
});

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusById', 'FindMeABike\Http\Controllers\FindMeABike@findById');

AlexaRoute::intent('/alexa', 'GetSpecificLocationStatusByLocation', 'FindMeABike\Http\Controllers\FindMeABike@findByLocation');

AlexaRoute::intent('/alexa', 'GetMyLocationStatus', 'FindMeABike\Http\Controllers\FindMeABike@myLocationStatus');