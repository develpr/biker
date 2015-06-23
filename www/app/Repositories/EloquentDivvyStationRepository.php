<?php  namespace FindMeABike\Repositories;

use FindMeABike\Contracts\Repositories\DivvyStationRepository;
use FindMeABike\Domain\Divvy\EloquentStation;
use FindMeABike\Domain\Divvy\Station;
use Illuminate\Config\Repository as Config;

class EloquentDivvyStationRepository implements DivvyStationRepository{

	/**
	 * @var \FindMeABike\Domain\Divvy\EloquentStation
	 */
	private $station;
	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	function __construct(EloquentStation $station, Config $config)
	{
		$this->station = $station;
		$this->config = $config;
	}

	/**
	 * @param $id
	 * @return Station | null
	 */
	public function findById($id)
	{
		return $this->station->active()->find($id);
	}

	/**
	 * Find a station with a given station id
	 * @param int $id
	 * @return Station | null
	 */
	public function findByStationId($stationId)
	{
		return $this->station->active()->where('station_id', $stationId)->first();
	}


	/**
	 * @param $latitude
	 * @param $longitude
	 * @return Station | null
	 */
	public function findByCoordinates($latitude, $longitude)
	{
		$proximity = $this->config->get('findmeabike.station_proximity_limit_miles');
		$nearbyStations = \DB::table('divvy_stations')
			->select(\DB::raw('*, SQRT(POW(69.1 * (latitude - ' . $latitude . '), 2) + POW(69.1 * (' . $longitude . ' - longitude) * COS(latitude / 57.3), 2)) AS distance'))
			->limit(1)
			->having('distance', '<', $proximity)
			->get();

		$result = $this->station->hydrate($nearbyStations)->first();

		return $result;
	}

	/**
	 * Given a json string containing multiple bike stations, insert into persistence.
	 * @param String $divvyData
	 */
	public function import($divvyData)
	{
		/** @var array $divvyData */

		$stations = [];

		foreach($divvyData as $divvyStation){
			$stations[] = [
				"station_id" => $divvyStation['id'],
				"station_name" => $divvyStation['stationName'],
				"available_docks" => $divvyStation['availableDocks'],
				"total_docks" => $divvyStation['totalDocks'],
				"latitude" => $divvyStation['latitude'],
				"longitude" => $divvyStation['longitude'],
				"status_value" => $divvyStation['statusValue'],
				"status_key" => $divvyStation['statusKey'],
				"available_bikes" => $divvyStation['availableBikes'],
				"street_address_1" => $divvyStation['stAddress1'],
				"street_address_2" => $divvyStation['stAddress2'],
				"city" => $divvyStation['city'],
				"postal_code" => $divvyStation['postalCode'],
				"location" => $divvyStation['location'],
				"altitude" => $divvyStation['altitude'],
				"test_station" => $divvyStation['testStation'],
				"last_communication_time" => $divvyStation['lastCommunicationTime'],
				"landmark" => $divvyStation['landMark'],
			];
		}

		$this->station->truncate();
		$this->station->insert($stations);
	}


} 