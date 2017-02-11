<?php

class JSON {
	public static function parse($string, &$error = null) {
		$decoded = @json_decode($string, true);

		$error = json_last_error() != JSON_ERROR_NONE;

		return $decoded;
	}

	public static function stringify($object) {
		return json_encode($object, JSON_UNESCAPED_UNICODE);
	}

	public static function milk(&$object) {
		if (is_array($object)) {
			foreach ($object as $key => &$item) {
				if ($item === null) {
					unset($object[$key]);
				} else {
					static::milk($item);
				}
			}
		}
	}
}
