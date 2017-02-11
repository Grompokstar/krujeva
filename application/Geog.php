<?php

abstract class Geog {
	public static function point($lon, $lat) {
		return [
			'type' => 'Point',
			'coordinates' => [$lon, $lat]
		];
	}

	public static function pointWKT($lon, $lat) {
		return sprintf('SRID=4326;POINT(%f %f)', $lon, $lat);
	}
}
