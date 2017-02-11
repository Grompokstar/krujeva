<?php

namespace Security\Data {
	use Security\AuthType;

	class LDAPUsers extends TrackableRecord {
		public static $container = 'security.ldapusers';

		public static $fields = [
			'id' => ['int'],
			'userid' => ['int'],
			'accountname' => ['string'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];

		public static function insertUser($accountName, $displayName) {
			txbegin();

			$record = null;

			try {
				$user = Users::insert(['login' => $accountName, 'name' => $displayName, 'authtype' => AuthType::LDAP]);

				$record = static::insert(['userid' => $user['id'], 'accountname' => $accountName]);
			} catch (Exception $e) {
				txabort();

				throw $e;
			}

			txcommit();

			return $record;
		}
	}
}