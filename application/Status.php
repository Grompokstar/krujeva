<?php

class Status extends Enum {
	static public $order = [];

	static public function greater($left, $right) {
		return array_search($left, static::$order) > array_search($right, static::$order);
	}

	static public function lower($left, $right) {
		return array_search($left, static::$order) < array_search($right, static::$order);
	}

	static public function equal($left, $right) {
		return array_search($left, static::$order) == array_search($right, static::$order);
	}

	static public function next($status) {
		$index = array_search($status, static::$order);

		if ($index === false || $index == count(static::$order) - 1) {
			return null;
		}

		return static::$order[$index + 1];
	}
}
