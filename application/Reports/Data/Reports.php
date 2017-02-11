<?php

namespace Reports\Data {
	use Data\TrackableRecord;

	class Reports extends TrackableRecord {
		public static $container = 'reports.reports';
		
		public static $fields = [
			'id' => ['int'],
			'classname' => ['string'],
			'parameters' => ['string'],
			'status' => ['int'],
			'format' => ['int'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];
	}
}
