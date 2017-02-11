<?php

namespace Web {
	abstract class Session {
		private static $active = false;

		public static function init() {

			ini_set('session.cookie_httponly', 1);

			session_set_cookie_params(10800);

			session_start();

			static::$active = true;
		}

		public static function deinit() {
			if (static::$active) {
				session_write_close();

				static::$active = false;
			}
		}

		public static function id($id = null) {
			if ($id) {
				session_id($id);

				return true;
			} else {
				return session_id();
			}
		}

		public static function set($name, $value) {
			if (static::$active) {
				$_SESSION[$name] = $value;
			}
		}

		public static function get($name) {
			return (static::$active && isset($_SESSION[$name])) ? $_SESSION[$name] : null;
		}
	}
}
