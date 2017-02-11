<?php

namespace Dict\Data {
	class Cities extends \Dict\Data\RecordFilter {
		public static $container = 'dict.cities';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'areaid' => ['int'],
			'geog' => ['string'],
			'timezone' => ['string'],
		];

		public static $filter = [
			'cleanItems' => ['callback' => 'formFilter']
		];

		public static function formFilter($field, $fieldvalue, &$query) {

			$query = $query[0];

			switch ($field) {

				case 'cleanItems':
					$query->fields('cities.id, cities.name');
					break;
			}
		}

		public static function getRussianCities($offset = 0, $limit = 120) {

			return static::select([
				'fields' => '
					areas.name as areaname,
					cities.id,
					cities.name as cityname
					',
				'join' => [
					[
						'table' => 'dict.areas',
						'on' => 'areas.id = cities.areaid'
					]
				],
				'where' => 'areas.countryid = 213 and geog is null',
				'order' => 'areas.name, cities.name',
				'offset' => $offset,
				'limit' => $limit,
			]);
		}

		public static function nearest ($lat, $lng) {

			return static::select([
				'fields' => 'id, name',
				'result' => 'row',
				'limit' => 1,
				'order' => "geog <-> ST_SetSRID('POINT(".((float)$lng)." ". ((float)$lat).")'::geometry, 4326)"
			]);
		}

		public static function googleGeocode($lat, $lng) {

			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=". ((float)$lat)."," . ((float)$lng) . "&language=ru&key=AIzaSyCDV7fh1v6Se9UDtmU_pTP7rr7q-XUaD60";

			$curl = curl_init($url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($curl);

			curl_close($curl);

			$response = @json_decode($response, true);

			if (!$response) {
				return '';
			}

			if (!isset($response['results'])) {
				return '';
			}

			if (!isset($response['results'][0])) {
				return '';
			}

			if (!isset($response['results'][0]['address_components'])) {
				return '';
			}

			$items = $response['results'][0]['address_components'];

			$street = '';
			$number = '';

			foreach ($items as $item) {

				foreach($item['types'] as $type) {

					if ($type == 'street_number') {
						$number = $item['long_name'];
					}

					if ($type == 'route') {
						$street = $item['long_name'];
					}
				}
			}

			if (!$street) {
				return '';
			}

			return $street. ($number ? ', '. $number : '');
		}
	}
}
