<?php

namespace Trackers\Data {
	class Alarms extends Record {

		public static $container = 'trackers.alarms';

		public static $fields = [
			'id' => ['int'],
			'trackerid' => ['int'],
			'datetime' => ['string'],
			'type' => ['int'],
			'geog' => ['string'],
			'createddatetime' => ['string'],
			'smsid' => ['int']
		];
	}
}

 