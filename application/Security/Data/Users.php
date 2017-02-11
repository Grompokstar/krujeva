<?php

namespace Security\Data {
	use Security\AuthType;

	class Users extends TrackableRecord {
		public static $container = 'security.users';

		public static $fields = [
			'id' => ['int'],
			'roleid' => ['int'],
			'roles' => ['string'],
			'login' => ['string'],
			'password' => ['string'],
			'name' => ['string'],
			'authtype' => ['int'],
			'edsthumbprint' => ['string'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];

		public static $filter = [
			'login' => "lower(users.login) like lower('%' || $1 || '%')",
			'status' => "userprofiles.status = $1"
		];


		protected static $salt = '';

		public static function recordFilter(&$options = [], $filter = [], $context = null) {

			\Dict\Data\RecordFilters::formFilter(get_called_class(), $options, $filter, $context);
		}

		public static function formRecordFilterOptions(&$relation, &$query) {

			$relation = $relation[0];
			$query = $query[0];
		}

		public static function findCredentials($login, $password, $authType = null) {

			if ($login === '' || $password === '') {
				return null;
			}

			$query = \Security\Data\Users::select(['where' => 'login = $1', 'data' => [$login], 'limit' => 1, 'result' => 'query']);

			if (!$user = $query->row()) {
				return null;
			}

			if ($authType !== null && ($authType != $user['authtype'])) {
				return null;
			}

			global $application;

			if (!$application->security->validatePassword($password, $user)) {
				return null;
			}

			static::adjust($user, ['type', 'hide']);

			return $user;
		}

		public static function cleanPhone($phone) {
			return preg_replace('~[^0-9]+~', '', $phone);
		}

		public static function updatePassword($user) {

			$login = $user['login'];

			$cachetimeout = 4;

			$cache = \Data\Redis::get('Password.' . $login);

			if ($cache) {
				return false;
			}

			$password = static::generatePassword(4);

			static::update(['id' => $user['id'], 'password' => $password]);

			\Data\Redis::set('Password.' . $login, 'send', ['timeout' => $cachetimeout]);

			$return = $password;

			return $return;
		}

		public static function generatePassword($length) {
			//$chars = 'bdfghmnqrstuvwxz123456789';
			$chars = '123456789';
			$count = mb_strlen($chars);

			for ($i = 0, $result = ''; $i < $length; $i++) {
				$index = rand(0, $count - 1);
				$result .= mb_substr($chars, $index, 1);
			}

			return $result;
		}

		public static function password(&$user) {

			if (isset($user['password'])) {

				$user['password'] = \Security\Service::encryptPassword($user['password']);
			}

		}

		public static function getRole($user) {
			return isset($user['roleid']) ? Roles::get($user['roleid']) : null;
		}

		public static function getRoles($user, $options = []) {
			$roles = [];

			if (isset($user['roles'])) {
				$list = pgIntArrayDecode($user['roles']);

				$roles = Roles::select([
					'where' => 'id = any($1)',
					'data' => [pgIntArrayEncode($list)]
				]);

				if (in_array('access', $options)) {
					foreach ($roles as &$role) {
						Roles::readAccess($role);
					}
				}
			}

			return $roles;
		}

		public static function insert($record, $options = []) {
			if (isset($record['password'])) {
				static::password($record);
			}

			if (!isset($record['authtype'])) {
				$record['authtype'] = AuthType::Native;
			}

			static::toPGIntArray($record, ['roles']);

			return parent::insert($record, $options);
		}

		public static function update($record, $options = []) {

			if (isset($record['password'])) {
				static::password($record);
			} else if (\array_key_exists('password', $record)) {
				unset($record['password']);
			}

			static::toPGIntArray($record, ['roles']);

			return parent::update($record, $options);
		}

		public static function build(&$user, $options = []) {
			static::process($user, $options, function (&$item, $options) {
				if (!isset($item['rolename'])) {
					if ($role = Roles::get($item['roleid'])) {
						$item['rolename'] = $role['name'];
					}

					if (in_array('roles', $options)) {
						$item['roles'] = static::getRoles($item);
					}
				}
			});
		}
	}
}

 