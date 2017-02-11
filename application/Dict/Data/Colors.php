<?php

namespace Dict\Data {
	class Colors extends Record {
		public static $container = 'dict.colors';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'hex' => ['string']
		];
	}
}
