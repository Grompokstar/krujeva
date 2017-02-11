<?php
namespace Security\Data {
	class Urls extends Record {
		public static $container = 'security.urls';

		public static $fields = [
			'id' => ['int'],
			'url' => ['string'],
			'keyid' => ['int'],
			'access' => ['int']
		];

		public static function build(&$item, $options = []) {

			static::process($item, $options, function (&$item, $options) {

				if (isset($item['keyid']) && !isset($item['key'])) {
					if ($key = Keys::get($item['keyid'])) {
						$item['key'] = $key["name"];
					}
				}

			});

			return $item;
		}
	}
}
 