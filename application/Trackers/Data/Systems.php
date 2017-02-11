<?php

namespace Trackers\Data {
	class Systems extends Record {
		public static $container = 'trackers.systems';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string']
		];
	}
}
