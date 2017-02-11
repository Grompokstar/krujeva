<?php

namespace Dict\Data {
	class VehicleModels extends Record {
		public static $container = 'dict.vehiclemodels';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string']
		];
	}
}
