<?php

namespace Trackers\Data {
	use Data\Memcached;

	class Trackers extends Record {
		public static $container = 'trackers.trackers';

		public static $fields = [
			'id' => ['int'],
			//'server' => ['int'],
			'typeid' => ['int'],
			'vendorid' => ['int'],
			'num' => ['string'],
			//'systems' => ['string'],
			'sim' => ['string'],
			'sim2' => ['string'],
			'description' => ['string'],
			//'attributes' => ['string']
		];

		public static function insert($record, $options = []) {

			static::toPGIntArray($record, 'systems');
			static::toJSON($record, ['attributes']);

			return parent::insert($record, $options);
		}

		public static function update($record, $options = []) {

			static::toPGIntArray($record, 'systems');
			static::toJSON($record, ['attributes']);

			return parent::update($record, $options);
		}

		public static function updateSet($record, $options = []) {

			static::toPGIntArray($record, 'systems');
			static::toJSON($record, ['attributes']);

			return parent::updateSet($record, $options);
		}

		public static function build(&$tracker, $options = []) {

			if ($tracker) {
				if (in_array('array', $options)) {
					$options = array_diff($options, ['array']);

					foreach ($tracker as &$item) {
						static::build($item, $options);
					}
				} else {
					if (!isset($tracker['typename'])) {
						if ($type = Types::get($tracker['typeid'])) {
							$tracker['typename'] = $type['name'];
						}
					}

					if (!isset($tracker['vendorname'])) {
						if ($vendor = Vendors::get($tracker['vendorid'])) {
							$tracker['vendorname'] = $vendor['name'];
						}
					}

					static::buildSystems($tracker);
				}
			}

			return $tracker;
		}

		private static function buildSystems(&$tracker) {

			$systems = Memcached::cached(function () {

				return array_column(Systems::select(), 'name', 'id');
			}, 'Trackers.Data.Trackers.systems', ['timeout' => 10]);

			$array = pgIntArrayDecode($tracker['systems']);

			$result = [];

			foreach ($array as $id) {
				if (isset($systems[$id])) {
					$result[] = ['id' => $id, 'name' => $systems[$id]];
				}
			}

			$tracker['systems'] = $result;
		}

		public static function getData($ids) {

			$limit = true;

			if (is_array($ids)) {

				if (!count($ids)) {
					return [];
				}

				$ids = implode(',', $ids);
				$limit = false;
			} else {
				if (!$ids) {
					return null;
				}
			}

			if (!count($ids)) {
				return [];
			}

			$options = [
				'fields' => 'trackers.*, types.name as type',
				'join' => [
					[
						'table' => 'trackers.types',
						'on' => 'types.id = trackers.typeid'
					]
				],
				'where' => 'trackers.id in (' . $ids . ')'
			];

			if ($limit) {
				$options['limit'] = 1;
			}

			$records = static::select($options);

			if (!$limit) {
				return $records;
			}

			return $records ? $records[0] : null;
		}
	}
}
 