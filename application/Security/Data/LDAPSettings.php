<?php

namespace Security\Data {
	class LDAPSettings extends TrackableRecord {
		public static $container = 'security.ldapsettings';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'value' => ['string'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];

		public static function getConfiguration() {
			$config = [];

			foreach (static::select() as $row) {
				$config[$row['name']] = $row['value'];
			}

			return $config;
		}
	}
}
