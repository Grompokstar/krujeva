<?php

namespace Data {
	class Redis extends Cache {

		/**
		 * @var \Redis
		 */
		protected static $redis = null;
        protected static $options = [];
        protected static $host = null;
        protected static $port = null;

		public static function init($options = []) {
			static::$options['timeout'] = option('timeout', $options, 3600);

			static::$host = option('host', $options);

			static::$port = option('port', $options);
        }

        protected static function connect() {

			if (!static::$redis) {

				static::$redis = new \Redis();

				static::$redis->connect(static::$host, static::$port);
			}
		}

		public static function close() {

			if (static::$redis) {

				static::$redis->close();
			}

			static::$redis = null;
		}

		public static function get($key) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->get($key);
		}

		public static function set($key, $value, $options = []) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			$timeout = option('timeout', $options, static::$options['timeout']);

			return static::$redis->set($key, $value, $timeout);
		}

		public static function del($key) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			static::$redis->del($key);

			return true;
		}

		public static function publish($channel, $message) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->publish($channel, $message);
		}

		public static function keys($pattern) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->getKeys($pattern);
		}

		public static function setBit($key, $offset, $value = 1) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->setBit($key, $offset, $value);
		}

		public static function lPush($key, $value1) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->lPush($key, $value1);
		}

		public static function lLen($key) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->lLen($key);
		}

		public static function lRange($key, $start, $end) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->lRange($key, $start, $end);
		}

		public static function lTrim($key, $start, $stop) {

			static::connect();

			if (!static::$redis) {
				return false;
			}

			return static::$redis->lTrim($key, $start, $stop);
		}
	}
}
 
