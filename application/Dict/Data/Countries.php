<?php

namespace Dict\Data {
	class Countries extends Record {
		public static $container = 'dict.countries';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
		];
	}
}
