<?php

namespace Web {

	class Cookies {
		public static function set($name, $value, $params = []) {
			setcookie($name, $value,
				static::param($params, 'expire', 0),
				static::param($params, 'path', '/'),
				static::param($params, 'domain', ''),
				static::param($params, 'secure', false),
				static::param($params, 'httponly', true)
			);
		}

		public static function get($name) {
			return static::param($_COOKIE, $name);
		}

		public static function has($name) {
			return array_key_exists($name, $_COOKIE);
		}

		private static function param($params, $name, $default = null) {
			return isset($params[$name]) ? $params[$name] : $default;
		}
	}
}
