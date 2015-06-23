<?php  namespace FindMeABike\Http\Controllers;

use FindMeABike\Contracts\Repositories\DivvyStationRepository;
use FindMeABike\Device;
use Illuminate\Routing\Controller as BaseController;
use \Alexa;

class FindMeABike extends  BaseController{

	/**
	 * @var \FindMeABike\Contracts\Repositories\DivvyStationRepository
	 */
	private $stationRepository;

	function __construct(DivvyStationRepository $stationRepository)
	{
		$this->stationRepository = $stationRepository;
	}

	public function findByLocation(){
		$lat = 41.8764032920;
		$long = -87.6203199186;

		$station = $this->stationRepository->findByCoordinates($lat, $long);

		if( ! $station )
			return Alexa::say("I can't find a station at that location. Try providing a street intersection or popular location.");

		return Alexa::say("The nearest I could find is the " . $station->getSpokenName() . " station, which has " . $station->availableBikes() . " available bikes and " . $station->availableDocks() . " docks available.")->endSession();
	}

	public function findById(){
		$stationId = intval(Alexa::slot('Id'));
		$station = $this->stationRepository->findByStationId($stationId);

		if( ! $station )
			return Alexa::say("I can't find a station with that ID. Try saying the station ID number again.");

		return Alexa::say("The " . $station->getSpokenName() . " station has " . $station->availableBikes() . " bikes available and " . $station->availableDocks() . " open docks.")->endSession();
	}

	public function myLocationStatus(){
		$device = \Alexa::device();

		if( ! $device ){
			$device = new Device;
			$device->setDeviceId(\Alexa::request()->getUserId());
			$device->save();
		}

		
	}
} 