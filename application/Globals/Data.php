<?php

function isRecord($object) {
	return is_a($object, 'Data\Record', true);
}

function txbegin() {
	\Data\Record::$connection->txbegin();
}

function txcommit() {
	\Data\Record::$connection->txcommit();
}

function txabort() {
	\Data\Record::$connection->txabort();
}

function query($query, $args = [], $params = []) {
	return \Data\Record::query($query, $args, $params);
}

function queryRows($query, $args = [], $params = []) {
	return \Data\Record::queryRows($query, $args, $params);
}

function queryRow($query, $args = [], $params = []) {
	return \Data\Record::queryRow($query, $args, $params);
}

function queryScalar($query, $args = [], $params = []) {
	return \Data\Record::queryScalar($query, $args, $params);
}

function pgIntArrayDecode($string) {
	if (is_array($string)) {
		return $string;
	}

	if (!$string || !is_string($string)) {
		return [];
	}

	$string = trim($string, '{}');
	$array = $string ? explode(',', $string) : [];

	foreach ($array as &$item) {
		$item = (int)$item;
	}

	return $array;
}

function pgIntArrayEncode($array) {
	if (is_string($array)) {
		return $array;
	}

	if (!$array || !is_array($array)) {
		return '{}';
	}

	foreach ($array as &$item) {
		$item = (int)$item;
	}

	return sprintf('{%s}', implode(',', $array));
}

function pgArrayDecode($string) {
	if (is_array($string)) {
		return $string;
	}

	if (!$string || !is_string($string)) {
		return [];
	}

	if ($string == '{}') {
		return [];
	}

	$array = [];

	if ($count = preg_match_all('/[\\{,]("(?:\\\\"|\\\\\\\\|[^"])*"|[^,"\\\\}]*)\\}?/', $string, $m)) {
		if (isset($m[1])) {
			foreach ($m[1] as $item) {
				$item = trim($item, '"');
				$item = str_replace('\\\\', '\\', $item);
				$item = str_replace('\\"', '"', $item);

				$array[] = $item;
			}
		}
	}

	return $array;
}

function pgArrayEncode($array) {
	if (is_string($array)) {
		return $array;
	}

	if (!$array || !is_array($array)) {
		return '{}';
	}

	foreach ($array as &$item) {
		$item = str_replace('\\', '\\\\', $item);
		$item = str_replace('"', '\\"', $item);
		$item = '"' . $item . '"';
	}

	return sprintf('{%s}', implode(',', $array));
}

function pgArg(&$arg = null) {
	if (!$arg) {
		$arg = '$1';
	} else {
		if (is_numeric($arg)) {
			$arg = '$' . ((int)$arg + 1);
		} else {
			$arg = '$' . ((int)substr($arg, 1) + 1);
		}
	}

	return $arg;
}

function pgArgLike(&$arg, $append = '%', $prepend = '%') {
	$ret = pgArg($arg);
	
	if ($prepend) {
		$ret = "'$prepend' || $ret";
	}

	if ($append) {
		$ret = "$ret || '$append'";
	}

	return $ret;
}

function pgData(&$data, $append = []) {
	if (!is_array($data)) {
		$data = [];
	}

	if (!is_array($append)) {
		$append = [$append];
	}

	$data = array_merge($data, $append);

	return $data;
}

function unique($array, $callback) {
	if (!is_callable($callback)) {
		return array_unique($array);
	} else {
		$result = [];
		$entries = [];

		for ($i = 0, $count = count($array); $i < $count; $i++) {
			$item = $array[$i];

			$entry = $callback($item);

			if (!isset($entries[$entry])) {
				$entries[$entry] = true;
				$result[] = $item;
			}
		}

		return $result;
	}
}
