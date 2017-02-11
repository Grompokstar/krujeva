<?php


namespace Krujeva\RedisData {

	class User extends Main {

		public static $key = 'bonus.';

		public static function setBonus($userid, $bonus) {
			return \Data\Redis::set(static::$key . $userid, $bonus, ['timeout' => 0]);
		}

		public static function getBonus($userid, $setIsNot = false) {

			$value = \Data\Redis::get(static::$key . $userid);

			if ($value) {
				return $value;
			}

			if (!$setIsNot) {
				return null;
			}

			$profile = \Krujeva\Data\UserProfiles::firstBy(['userid' => $userid]);

			if (!$profile) {
				return null;
			}

			static::setBonus($userid, $profile['bonus']);

			return $profile['bonus'];
		}

	}

}