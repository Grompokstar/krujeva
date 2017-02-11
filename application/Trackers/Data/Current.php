<?php

namespace Trackers\Data {
	class Current extends Record {

		public static $container = 'trackers.current';

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
			'geog' => ['string']
		];
	}
}
