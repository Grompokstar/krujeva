<?php

ini_set('display_errors', 'on');
ini_set('error_reporting', '-1');

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');




$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";



$cities = \Dict\Data\Cities::select([
	'fields' => '*, st_asgeojson(ST_Centroid(geog)) as centroid',
	'where' => 'geog is not null',
	'order' => 'random()'
]);


foreach ($cities as $city) {

	var_dump($city['name']);

	$geog = JSON::parse($city['centroid']);

	$lat = ((float)$geog['coordinates'][1]);

	$lng = ((float)$geog['coordinates'][0]);

	$time = time();


	$url = "https://maps.googleapis.com/maps/api/timezone/json?location=" . ((float)$lat) . "," . ((float)$lng) ."&timestamp=". $time."&key=AIzaSyAe6irvvVlnqpI2yTm9oz4RzJMRUU_REmo";

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($curl);

	curl_close($curl);

	$response = @json_decode($response, true);

	if (!$response) {
		var_dump($city);
		exit();
	}

	if (!isset($response['rawOffset'])) {

		var_dump('error', $city['name']);

		continue;
	}

	$timezome = $response['rawOffset'] / 3600;

	if ($timezome > 0) {
		$timezome = '+'. $timezome;
	} else {
		$timezome = '-'. $timezome;
	}

	\Dict\Data\Cities::update(['timezone' => $timezome, 'id' => $city['id'] ]);

	var_dump($city['name']);
	//var_dump($timezome);
	//exit();

}
