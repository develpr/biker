<?php  namespace Biker\Http\Controllers;

use Develpr\AlexaApp\Response\Card;
use Biker\Contracts\GeocodingService;
use Biker\Contracts\Repositories\DivvyStationRepository;
use Biker\Contracts\Station;
use Biker\Services\Data\DivvyImporter;
use Illuminate\Routing\Controller as BaseController;
use \Alexa;

class Biker extends  BaseController{

	/**
	 * @var \Biker\Contracts\Repositories\DivvyStationRepository
	 */
	private $stationRepository;
	/**
	 * @var \Biker\Contracts\GeocodingService
	 */
	private $geocodingService;

	function __construct(DivvyStationRepository $stationRepository, DivvyImporter $divvyImporter, GeocodingService $geocodingService)
	{
		$divvyImporter->refreshData();
		$this->stationRepository = $stationRepository;
		$this->geocodingService = $geocodingService;
	}

	/**
	 * Handle the launch event for the app
	 */
	public function handleLaunch(){
		return Alexa::say("Welcome to Bike Finder. You can get information about a station using a location or ID, or setup your home station. What would you like to do?");
	}

	/**
	 * Handle the launch event for the app
	 */
	public function handleSessionEnded(){
		return Alexa::say("Goodbye")->endSession();
	}

	public function findByLocation(){

		$station = $this->findStationFromLocation();

		if( ! $station ){
			return Alexa::say("I can't find a station near that location. You could try providing a different street intersection or popular destination.");
		}

		return Alexa::say("The nearest I could find is the " . $station->getSpokenName() . " station which is " . $this->getSpokenDistance($station->distance) . " away and has " . $station->availableBikes() . " available bikes and " . $station->availableDocks() . " docks available.")->endSession();
	}

	public function setByLocation(){
		$station = $this->findStationFromLocation();

		if( ! $station ){
			return Alexa::say("I can't find a station near that location. You could try providing a different street intersection, address, or popular destination.");
		}
		else{
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
			return Alexa::say("It doesn't look like you have a home station setup. Which station would you like to be your home station?");

	}

	public function setLocationId(){
		$id = intval(Alexa::slot('Id'));

		$station = $this->stationRepository->findByStationId($id);

		if( ! $station ){
			return Alexa::say("I can't find a station with that ID. Try again, or use the station location instead.");
		}
		else{
			return Alexa::say("I've set the " . $station->getSpokenName() . " station as your home station.");
		}
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

	/**
	 * Adds pauses between characters of device code for better speech output
	 * @param string $deviceCode
	 * @return string
	 */
	private function getSpokenDeviceCode($deviceCode){
		$spoken = '';
		foreach(str_split($deviceCode) as $codeCharacter){
			$spoken .= $codeCharacter . ', ';
		}
		return $spoken;
	}

} 