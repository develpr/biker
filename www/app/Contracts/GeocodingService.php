<?php namespace FindMeABike\Contracts;

interface GeocodingService {
	/**
	 * Attempts to geocode a given location string and returns an array of key/values with latitude and longitude
	 *
	 * @param $location the words that represent the location we are geocoding
	 * @param $local a simple string to be appended to the search to narrow the search
	 * @return array | null
	 */
	public function geocode($location, $local = null);
} 