<?php

namespace Security {
	use Security\Auth\IAuth;
	use System\Code;
	use System\Exception;

	abstract class Security {
		/**
		 * @var Context
		 */
		public static $context = null;

		private static $suspense = 0;

		public static function init($context) {
			static::$context = $context;

			//static::$context->read();
		}

		public static function deinit() {
			static::$context->write();
		}

		public static function signIn($auth, $options = []) {
			if (!is_array($auth)) {
				$auth = [$auth];
			}

			/**
			 * @var IAuth $class
			 */
			foreach ($auth as $class) {
				if (is_a($class, 'Security\Auth\IAuth', true)) {
					if ($class::auth(static::$context, $options)) {
						return true;
					}
				}
			}

			return false;
		}

		public static function signOut() {
			if (static::$context) {
				Security::$context->init();
			}

			return true;
		}

		public static function signedIn() {
			return static::$context && static::$context->isValid();
		}

		public static function check($key, $mode) {
			if (static::$suspense) {
				return true;
			}

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

		public static function verify($key, $mode) {
			if (!static::check($key, $mode)) {
				$mode = AccessMode::title($mode);

				throw new Exception("Доступ запрещён $key ($mode)", Code::AccessDenied);
			}

			return true;
		}

		public static function unsafe($callback) {
			static::suspend();

			$callback();

			static::resume();
		}

		public static function suspend() {
			static::$suspense++;
		}

		public static function resume() {
			if (static::$suspense) {
				static::$suspense--;
			}
		}
	}
}

 