<?php

namespace Krujeva\Data {

	class Feedback extends Record {
		public static $container = ' krujeva.feedback';

		public static $fields = [
			'id' => ['int'],
			'name' => ['string'],
			'phone' => ['string'],
			'text' => ['string'],
			'datetime' => ['string'],
		];

		public static $filter = [
		];

		public static function insert($record, $options = []) {

			static::formRecord($record);

			return parent::insert($record, $options);
		}

		//заполняем поля - если они не были заполнены пользователем
		private static function formRecord(&$record) {

			$record['datetime'] = \Utils::localeDatetime(time(), '+3');
		}
	}
}
