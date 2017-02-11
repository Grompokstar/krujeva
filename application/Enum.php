<?php

namespace {
	abstract class Enum {
		public static function validate($value) {
			return in_array($value, (new \ReflectionClass(get_called_class()))->getConstants());
		}

		public static function items() {
			return (new \ReflectionClass(get_called_class()))->getConstants();
		}

		public static function has($value) {
			return in_array($value, static::items());
		}

		public static function name2Value($name) {
			$constants = (new \ReflectionClass(get_called_class()))->getConstants();
			return isset($constants[$name]) ? $constants[$name] : null;
		}

		public static function value2Name($value) {
			return array_search($value, (new \ReflectionClass(get_called_class()))->getConstants());
		}

		public static function title($value) {
			return static::value2Name($value);
		}

		public static function titles($values) {
			$titles = [];

			foreach ($values as $value) {
				$titles[] = static::title($value);
			}

			return implode(', ', $titles);
		}

		public static function each($callback) {
			foreach (static::items() as $name => $value) {
				$callback($value, $name);
			}
		}
	}
}
 