<?php

namespace Security\Data {
	class Roles extends TrackableRecord {
		public static $container = 'security.roles';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'description' => ['string'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];

		public static function insert($record, $options = []) {
			txbegin();

			$access = isset($record['access']) ? $record['access'] : [];

			$record = parent::insert($record, $options);

			foreach ($access as $item) {
				static::grant($record, $item['id'], pgIntArrayDecode($item['mode']));
			}

			txcommit();

			return $record;
		}

		public static function update($record, $options = []) {
			txbegin();

			$access = isset($record['access']) ? $record['access'] : [];

			$record = parent::update($record, $options);

			foreach ($access as $item) {
				static::grant($record, $item['id'], pgIntArrayDecode($item['mode']));
			}

			txcommit();

			return $record;
		}

		public static function readAccess(&$role) {
			$role['access'] = Keys::select([
				'fields' => 'keys.*, coalesce(access.mode, array[]::int[]) as mode',
				'join' => [
					[
						'table' => 'security.access',
						'on' => 'keys.id = access.keyid and access.roleid = $1',
						'type' => 'left'
					]
				],
				'data' => [$role['id']],
				'order' => 'keys.name'
			]);
		}

		public static function grant($role, $keyId, $mode) {
			$access = Access::firstBy(['roleid' => $role['id'], 'keyid' => $keyId]);

			if ($access) {
				$access['mode'] = pgIntArrayEncode($mode);

				$access = Access::update($access);
			} else {
				$access = Access::insert(['roleid' => $role['id'], 'keyid' => $keyId, 'mode' => pgIntArrayEncode($mode)]);
			}

			return $access;
		}

		public static function revoke($role, $key) {
			Access::removeSet([
				'where' => 'roleid = $1 and keyid = $2',
				'data' => [$role['id'], $key['id']]
			]);
		}
	}
}
