<?php

namespace System {
	use Data\Connection;

	class NumSeq {
		/**
		 * @var Connection
		 */
		public static $connection;

		public static function next($name) {
			return (int)static::$connection->queryScalar('select system.numseqget($1)', [$name]);
		}
	}
}
