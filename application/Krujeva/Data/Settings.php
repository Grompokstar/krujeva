<?php

namespace Krujeva\Data {

	class Settings extends Record {
		public static $container = ' krujeva.settings';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'value' => ['string']
		];

		public static $filter = [
		];

		public static function insert($record, $options = []) {

			$old = static::select(['result' => 'row', 'limit' => 1, 'where' => 'name = $1', 'data' => [$record['name']]]);

			if ($old) {

				$r = parent::update(array_merge($record, ['id'=> $old['id']]));

			} else {

				$r = parent::insert($record);
			}

			return $r;
		}

		public static function getByName($name) {

			return static::select([
				'result' => 'scalar',
				'fields' => 'value',
				'limit' => 1,
				'where' => 'name = $1',
				'data' => [$name]
			]);
		}
	}
}
