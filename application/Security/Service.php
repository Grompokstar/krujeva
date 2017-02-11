<?php

namespace Security {

	class Service {

		public static $context = null;

		public static function restoreContext() {

			global $application;

			$config = $application->configuration;

			if (!static::$context) {

				static::$context = new $config['security']['context']();
			}

			$headerKey = 'HTTP_'.strtoupper($config['security']['headerKey']);

			if(isset($_SERVER[$headerKey]) && $_SERVER[$headerKey]){

				$contextId = $_SERVER[$headerKey];

			} else {

				$contextId = \Web\Cookies::get($config['security']['sessionKey']);

			}

			if ($contextId) {

				static::$context->restore($contextId);
			}

		}

		public static function encryptPassword($password) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		public static function logout() {

			if (!context('user.id')) {

				return true;
			}

			static::$context->remove();

			global $application;

			$config = $application->configuration;

			\Web\Cookies::set($config['security']['sessionKey'], null);

			return true;
		}

		public static function login($login, $password, $trusted = false, $setCookie = true) {

			//if (context('user.id')) {
			//
			//	return context()->data;
			//}

			$user = \Security\Data\Users::firstBy(['login' => $login]);

			if (!$user) {
				return false;
			}

			if (!$trusted) {

				if (!static::validatePassword($password, $user['password'])) {
					return false;
				}
			}

			unset($user['password']);

			$contextId = static::createContextId();

			static::$context->setUser($user, $contextId);

			static::$context->save();

			global $application;

			$config = $application->configuration;

			if ($setCookie) {

				\Web\Cookies::set($config['security']['sessionKey'], $contextId, [
					'expire' => time() + static::$context->getTimeout()
				]);
			}

			return static::$context->data;
		}

		public static function validatePassword($password, $userpassword) {

			if (!password_verify($password, $userpassword)) {
				return false;
			}

			return true;
		}

		public static function createContextId() {

			$tv = gettimeofday();

			$lcg['s1'] = $tv['sec'] ^ (~$tv['usec']);
			$lcg['s2'] = posix_getpid();

			$q = (int)($lcg['s1'] / 53668);
			$lcg['s1'] = (int)(40014 * ($lcg['s1'] - 53668 * $q) - 12211 * $q);

			if ($lcg['s1'] < 0) {
				$lcg['s1'] += 2147483563;
			}

			$q = (int)($lcg['s2'] / 52774);
			$lcg['s2'] = (int)(40692 * ($lcg['s2'] - 52774 * $q) - 3791 * $q);

			if ($lcg['s2'] < 0) {
				$lcg['s2'] += 2147483399;
			}

			$z = (int)($lcg['s1'] - $lcg['s2']);

			if ($z < 1) {
				$z += 2147483562;
			}

			$lcg = $z * 4.656613e-10;

			$tv = gettimeofday();

			$addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'rgba';

			return md5(sprintf("%.15s%ld%ld%0.8f", $_SERVER['REMOTE_ADDR'], $tv['sec'], $tv['usec'], $lcg * 10));
		}

		public static function verify($key, $mode) {

			if (!static::check($key, $mode)) {

				$mode = AccessMode::title($mode);

				throw new \System\Exception("Доступ запрещён $key ($mode)", \System\Code::AccessDenied);
			}

			return true;
		}

		public static function check($key, $mode) {

			if (!$key) {
				return true;
			}

			/**
			 * @var Data\Roles $role
			 */
			$data = static::$context->data;

			if (!$data) {
				return false;
			}

			$role = isset($data['role']) ? $data['role'] : null;

			if (!$role) {
				return false;
			}

			if ($role['name'] == 'root') {
				return true;
			}

			$granted = isset($role['access'][$key]) ? $role['access'][$key] : null;

			if (!$granted) {
				return false;
			}

			$granted = pgIntArrayDecode($granted);

			if (!is_array($mode)) {
				$mode = [$mode];
			}

			return !array_diff($mode, $granted);
		}

	}


}
