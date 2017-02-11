<?php

namespace Trackers\Data {
	class Vendors extends Record {
		public static $container = 'trackers.vendors';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string']
		];
	}
}
