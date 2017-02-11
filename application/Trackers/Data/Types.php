<?php

namespace Trackers\Data {
	class Types extends Record {

		public static $container = 'trackers.types';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			//'description' => ['string']
		];
	}
}
 