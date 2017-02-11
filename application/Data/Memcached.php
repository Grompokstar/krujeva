<?php

namespace Data {
	class Memcached extends Cache {
		/**
		 * @var \Memcached
		 */
		private static $memcached = null;
		private static $options = [];

		public static function init($options = []) {
			static::$options['timeout'] = option('timeout', $options, 3600);

			if (!static::$memcached) {
				static::$memcached = new \Memcached();
				static::$memcached->setOption(\Memcached::OPT_COMPRESSION, false);

				$host = option('host', $options, '127.0.0.1');
				$port = option('port', $options, 11211);

				static::$memcached->addServer($host, $port);
			}
		}

		public static function close() {
			static::$memcached->quit();
			static::$memcached = null;
		}

		public static function get($key) {
			if (!static::$memcached) {
				return false;
			}

			return static::$memcached->get($key);
		}

		public static function set($key, $value, $options = []) {
			if (!static::$memcached) {
				return false;
			}

			$timeout = option('timeout', $options, static::$options['timeout']);

			if ($timeout !== null) {
				$timeout = time() + $timeout;
			}

			return static::$memcached->set($key, $value, $timeout);
		}
	}
}
