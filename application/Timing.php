<?php

class Timing {
	private static $times = [];
	public static $default = 'default';
	public static $mode = 'console';

	public static function reset($name = null) {
		if (!$name) {
			$name = static::$default;
		}

		static::$times[$name] = microtime(true);
	}

	public static function get($name = null) {
		if (!$name) {
			$name = static::$default;
		}

		if (!isset(static::$times[$name])) {
			static::reset($name);
		}

		$duration = microtime(true) - static::$times[$name];

		static::reset($name);

		return $duration;
	}

	public static function show($title = null, $name = null) {
		if (!$name) {
			$name = static::$default;
		}

		$duration = static::get($name);

		$ending = "\n";

		switch (static::$mode) {
			case 'web':
				$ending = '<br>';
				break;
			case 'mixed':
				$ending = "<br>\n";
		}

		if ($title) {
			$title .= ': ';
		}
		echo "{$title}elapsed $duration$ending";
	}
}
