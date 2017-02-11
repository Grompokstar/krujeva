<?php

namespace Web {
	class Request {
		private static $method = RequestMethod::Unknown;
		private static $isXHR = false;

		public static function init() {
			switch ($_SERVER['REQUEST_METHOD']) {
				case 'GET':
					self::$method = RequestMethod::Get;
					break;
				case 'POST':
					self::$method = RequestMethod::Post;
					break;
				default:
					self::$method = RequestMethod::Unknown;
			}

			self::$isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' : false;
		}

		public static function method() {
			return self::$method;
		}

		public static function isGet() {
			return self::$method == RequestMethod::Get;
		}

		public static function isPost() {
			return self::$method == RequestMethod::Post;
		}

		public static function isXHR() {
			return self::$isXHR;
		}

		public static function data() {
			if (self::$method === RequestMethod::Get) {
				return $_GET;
			} else if (self::$method === RequestMethod::Post) {
				return $_POST;
			}

			return [];
		}
	}

	Request::init();
}
