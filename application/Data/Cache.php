<?php

namespace Data {
	class Cache {
		/**
		 * @var Cache
		 */
		private static $class = null;

		public static function init($options = []) {
			self::$class = option('class', $options);

			if ($class = self::$class) {
				$class::init($options);
			}
		}

		public static function close() {
			if ($class = self::$class) {
				$class::close();
			}
		}

		public static function get($key) {
			if ($class = self::$class) {
				return $class::get($key);
			} else {
				return null;
			}
		}

		public static function set($key, $value, $options = []) {
			if ($class = self::$class) {
				return $class::set($key, $value, $options);
			} else {
				return null;
			}
		}

		public static function cached($callback, $key, $options = []) {
			$result = static::get($key);

			if ($result === false) {
				$result = $callback();
				static::set($key, $result, $options);
			}

			return $result;
		}
	}
}
 