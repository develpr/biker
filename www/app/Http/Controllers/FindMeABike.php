<?php  namespace FindMeABike\Http\Controllers;

use FindMeABike\Contracts\GeocodingService;
use FindMeABike\Contracts\Repositories\DivvyStationRepository;
use FindMeABike\Device;
use FindMeABike\Contracts\Station;
use FindMeABike\Services\Data\DivvyImporter;
use Illuminate\Routing\Controller as BaseController;
use \Alexa;

class FindMeABike extends  BaseController{

	/**
	 * @var \FindMeABike\Contracts\Repositories\DivvyStationRepository
	 */
	private $stationRepository;
	/**
	 * @var \FindMeABike\Contracts\GeocodingService
	 */
	private $geocodingService;

	function __construct(DivvyStationRepository $stationRepository, DivvyImporter $divvyImporter, GeocodingService $geocodingService)
	{
		$divvyImporter->refreshData();
		$this->stationRepository = $stationRepository;
		$this->geocodingService = $geocodingService;
	}

	public function findByLocation(){

		$station = $this->findStationFromLocation();

		if( ! $station ){
			return Alexa::say("I can't find a station near that location. Try providing a street intersection or popular location.");
		}

		return Alexa::say("The nearest I could find is the " . $station->getSpokenName() . " station which is " . $this->getSpokenDistance($station->distance) . " away and has " . $station->availableBikes() . " available bikes and " . $station->availableDocks() . " docks available.")->endSession();
	}

	public function setByLocation(){
		$station = $this->findStationFromLocation();
		$device = $this->retrieveOrCreateDevice();

		if( ! $station ){
			return Alexa::say("I can't find a station near that location. Try providing a street intersection or popular location.");
		}
		else{
			$device->station()->associate($station);
			return Alexa::say("I found the " . $station->getSpokenName() . " station which is " . $this->getSpokenDistance($station->distance) . " away and set it as your home station.");
		}
	}

	private function findStationFromLocation(){
		$spokenLocation = Alexa::slot('Location');
		$geocodedLocation = $this->geocodingService->geocode($spokenLocation);

		if( ! $geocodedLocation ){
			return null;
		}

		$station = $this->stationRepository->findByCoordinates($geocodedLocation['latitude'], $geocodedLocation['longitude']);

		return $station;
	}

	public function findById(){
		$stationId = intval(Alexa::slot('Id'));
		$station = $this->stationRepository->findByStationId($stationId);

		if( ! $station )
			return Alexa::say("I can't find a station with that ID. Try saying the station ID number again.");

		return Alexa::say($station->getFullSpokenStatus())->endSession();
	}

	public function myLocationStatus(){
		$device = $this->retrieveOrCreateDevice();

		if( ! $device->station ){
			return Alexa::say("It doesn't look like you have a home station setup. Which station would you like to be your home station?");
		}
		else{
			return Alexa::say($device->station->getFullSpokenStatus())->endSession();
		}

	}

	public function setLocationId(){
		/** @var  $device */
		$device = $this->retrieveOrCreateDevice();
		$id = intval(Alexa::slot('Id'));

		$station = $this->stationRepository->findByStationId($id);

		if( ! $station ){
			return Alexa::say("I can't find a station with that ID. Try again, or use the station location instead.");
		}
		else{
			$device->station()->associate($station);
			return Alexa::say("I've set the " . $station->getSpokenName() . " station as your home station.");
		}
	}

	/**
	 * @return \Develpr\AlexaApp\Contracts\AmazonEchoDevice|Device|null
	 */
	private function retrieveOrCreateDevice(){
		$device = Alexa::device();

		if( ! $device ){
			$device = new Device;
			$device->setDeviceId(\Alexa::request()->getUserId());
			$device->save();
		}

		return $device;
	}

	private function getSpokenDistance($decimalMiles){

		if($decimalMiles >= .25){
			$result = $this->roundUpToAny($decimalMiles * 100);
			$result = $result / 100;
			switch($result){
				case $result >= 1:
					return $result . " miles";
				case $result == 1:
					return "around one mile";
				case $result == .75:
					return "three quarters of a mile";
				case $result == .5:
					return "around half a mile";
				case $result == .25:
					return "around a quater mile";
			}
		}
		else{
			return round($decimalMiles * 5280) . " feet";
		}
	}

	private function roundUpToAny($n,$x=25) {
		return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
	}

} 