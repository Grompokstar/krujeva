<?php

namespace Data {
	class SessionRedis extends Redis {

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
	}
}
 