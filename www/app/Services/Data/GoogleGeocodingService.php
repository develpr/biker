<?php  namespace FindMeABike\Services\Data;

use FindMeABike\Contracts\GeocodingService;
use Illuminate\Config\Repository as Config;

class GoogleGeocodingService implements GeocodingService {

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * Attempts to geocode a given location string and returns an array of key/values with latitude and longitude
	 *
	 * @param $location the words that represent the location we are geocoding
	 * @param $local a simple string to be appended to the search to narrow the search
	 * @return array | null
	 */
	public function geocode($location, $local = null)
	{
		//Get the default local - this is just a simple string that will be appending to narrow search results
		if( ! $local )
			$local = $this->config->get('findmeabike.geocoding.default_local');

		$googleGeocodeData = $this->fetchGoogleGeocode($location, $local);
		$latLong = $this->extractLatAndLong($googleGeocodeData);

		return $latLong;
	}

	private function fetchGoogleGeocode($location, $local){

		$uri = $this->buildGeocodeRequestUri($location, $local);

		try{
			$result = file_get_contents($uri);
		}catch(\Exception $e){
			//todo: log/email/report this!
			return null;
		}

		return json_decode($result, true);
	}

	private function buildGeocodeRequestUri($location, $local){
		$uri = $this->config->get('findmeabike.geocoding.google_base_url');
		$uri .= '?key=' . $this->config->get('findmeabike.geocoding.google_maps_api_key');
		$uri .= '&address=' . urlencode($location);

		if($local)
			$uri .= urlencode(", " . $local);

		return $uri;
	}

	private function extractLatAndLong($googleGeocodeData)
	{
		if( ! $googleGeocodeData || ! is_array($googleGeocodeData) || $googleGeocodeData['status'] != "OK" )
			return null;

		$googleGeocodeData = $googleGeocodeData['results'];
		reset($googleGeocodeData);
		$key = key($googleGeocodeData);
		$googleGeocodeData = $googleGeocodeData[$key];

		$latitude = array_get($googleGeocodeData, 'geometry.location.lat');
		$longitude = array_get($googleGeocodeData, 'geometry.location.lng');

		return [
			'latitude' 	=> $latitude,
			'longitude' => $longitude
		];
	}

} 