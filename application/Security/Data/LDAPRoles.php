<?php

namespace Security\Data {
	class LDAPRoles extends TrackableRecord {
		public static $container = 'security.ldaproles';

		public static $fields = [
			'id' => ['int'],
			'roleid' => ['int'],
			'dn' => ['string'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];
	}
}