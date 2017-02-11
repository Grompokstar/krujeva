<?php

namespace Security\Data {
	class Access extends TrackableRecord {
		public static $container = 'security.access';

		public static $fields = [
			'id' => ['int'],
			'roleid' => ['int'],
			'keyid' => ['int'],
			'mode' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];
	}
}
