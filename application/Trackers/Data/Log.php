<?php

namespace Trackers\Data {
	class Log extends Record {

		public static $container = 'trackers.log';

		public static $fields = [
			'id' => ['int'],
			'trackerid' => ['int'],
			'datetime' => ['string'],
			'createddatetime' => ['string'],

			'lon' => ['double'],
			'lat' => ['double'],
			'speed' => ['int'],
			'odometer' => ['int'],
			'course' => ['int'],
			'satsglonass' => ['int'],
			'satsgps' => ['int'],
			'geog' => ['string'],
			'run' => ['double'],
			'duration' => ['int'],
			'valid' => ['int']
		];
	}
}

