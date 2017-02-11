<?php

function datetime_check_format($date, $format = 'd.m.Y H:i:s') {
	$array = date_parse_from_format($format, $date);

	return !count($array['errors']);
}

function datetime_convert($date, $fromFormat = 'd.m.Y H:i:s', $toFormat = 'Y-m-d H:i:s') {
	if (datetime_check_format($date, $fromFormat)) {
		return date($toFormat, strtotime($date));
	}

	return $date;
}

function datetime($timestamp = null, $format = 'Y-m-d H:i:s') {
	if (!$timestamp) {
		$timestamp = time();
	}

	return date($format, $timestamp);
}
