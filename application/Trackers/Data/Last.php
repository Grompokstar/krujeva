<?php

namespace Trackers\Data {
	class Last extends Record {

		public static $container = 'trackers.last';

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

 