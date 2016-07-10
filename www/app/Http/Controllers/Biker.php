<?php  namespace Biker\Http\Controllers;

use Develpr\AlexaApp\Response\Card;
use Biker\Contracts\GeocodingService;
use Biker\Contracts\Repositories\DivvyStationRepository;
use Biker\Device;
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
		$device = $this->retrieveOrCreateDevice();
		if( $device->station ){
			return Alexa::say("Welcome to Bike Finder. You can get information for your home station, or find information about a different bike station. What would you like to do?");
		}
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
		$device = $this->retrieveOrCreateDevice();

		if( ! $station ){
			return Alexa::say("I can't find a station near that location. You could try providing a different street intersection, address, or popular destination.");
		}
		else{
			$device->station()->associate($station)->save();
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
			$device->station()->associate($station)->save();
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

	public function getDeviceCode(){
		$device = $this->retrieveOrCreateDevice();

		return Alexa::say("Your device's code for web registration is " . $this->getSpokenDeviceCode($device->device_code) . '. You reset this code at any time by saying, "reset my device code."')
			->withCard(new Card("Your Web Device Code", "", "Your code for web registration is " . $device->device_code))
			->endSession();
	}

	public function resetDeviceCode(){
		$device = $this->retrieveOrCreateDevice();
		$device->generateDeviceCode()->save();

		return Alexa::say("The code for you device has been reset and is now " . $this->getSpokenDeviceCode($device->device_code) . '.')->endSession();
	}

	public function detachAccount(){
		$device = $this->retrieveOrCreateDevice();

		if(! $device->user ){
			return Alexa::say("There is no user account associated with this device currently")->endSession();
		}
		else{
			return Alexa::ask("Are you sure you want to disconnect this device from it's web account?")->sendResponseTo('ConfirmDetachAccount');
		}
	}

	public function confirmDetachAccount(){
		$response = strtolower(trim(Alexa::slot('PromptResponse')));

		if($response == "yes" || $response == "sure" || $response === 1){
			$device = $this->retrieveOrCreateDevice();
			/** @var Device $device */
			$device->user()->dissociate();
			$device->generateDeviceCode();
			$device->save();
			return Alexa::say("Your device is no longer associated with a web account")->endSession();
		}
		else{
			return Alexa::say("I'll leave things as they are.")->endSession();
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