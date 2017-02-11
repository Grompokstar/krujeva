<?php

namespace Dict\Data {
	class Areas extends \Dict\Data\RecordFilter {
		public static $container = 'dict.areas';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'countryid' => ['int']
		];

		public static $filter = [
			'country' => ['callback' => 'formFilter']
		];

		public static function formFilter($field, $fieldvalue, &$query) {

			$query = $query[0];

			switch ($field) {

				case 'country':

					if ($fieldvalue == 'russia') {

						$query->where('areas.countryid = $1', [213]);
					}
					break;
			}
		}

	}
}
