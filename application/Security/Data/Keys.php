<?php
namespace Security\Data {
	class Keys extends Record {
		public static $container = 'security.keys';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'description' => ['string']
		];
	}
}
 