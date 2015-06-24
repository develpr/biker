<?php  namespace FindMeABike\Contracts\Repositories;

use FindMeABike\Contracts\Station;

interface DivvyStationRepository {

	/**
	 * @param $id
	 * @return Station | null
	 */
	public function findById($id);

	/**
	 * Find a station with a given station id
	 * @param int $id
	 * @return Station | null
	 */
	public function findByStationId($stationId);

	/**
	 * @param $latitude
	 * @param $longitude
	 * @return Station | null
	 */
	public function findByCoordinates($latitude, $longitude);

	/**
	 * Given the data containing multiple bike stations, insert into data store
	 * @param $divvyData
	 */
	public function import($divvyData);

} 