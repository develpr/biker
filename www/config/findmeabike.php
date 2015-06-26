<?php

return [
	'station_proximity_limit_miles' => .5,

	'data_age_threshold' => 600,

//	'data_source_uri' => 'http://findmeabike.dev/divvy_json.json',
	'data_source_uri' => 'http://www.divvybikes.com/stations/json',

	'geocoding' => [
		'enable_google' => true,
		'google_base_url' => 'https://maps.googleapis.com/maps/api/geocode/json',
		'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY',''),
		'default_local' => 'Chicago',
	],

	'web' => [
		'device_code_length' => 8,
	],
];

