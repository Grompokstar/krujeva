<?php

namespace Data {
	class TrackableRecord extends Record {
		public static $fields = [
			'id' => ['int'],
			'createddatetime' => ['string'],
			'modifieddatetime' => ['string'],
			'creator' => ['string'],
			'modifier' => ['string']
		];

		public static $auto = ['id', 'createddatetime', 'modifieddatetime'];

		public static function isTrackable($object) {
			return is_a($object, 'Data\TrackableRecord', true);
		}

		public static function initTrackable(&$record, $mode = 'update') {
			$user = context('user');

			switch ($mode) {
				case 'insert':
					$record['creator'] = ['id' => $user['id'], 'login' => $user['login'], 'name' => $user['name']];
					$record['modifier'] = '{}';
					break;
				case 'update':
					unset($record['creator']);
					$record['modifier'] = ['id' => $user['id'], 'login' => $user['login'], 'name' => $user['name']];
					break;
			}
		}

		public static function insert($record, $options = []) {
			if (!in_array('raw', $options, true)) {
				static::initTrackable($record, 'insert');
			}

			static::toJSON($record, ['creator', 'modifier']);

			return parent::insert($record, $options);
		}

		public static function update($record, $options = []) {
			if (!in_array('raw', $options, true)) {
				static::initTrackable($record, 'update');
			}

			static::toJSON($record, ['creator', 'modifier']);

			return parent::update($record, $options);
		}

		public static function updateSet($record, $options = []) {
			if (!in_array('raw', $options, true)) {
				static::initTrackable($record, 'update');
			}

			static::toJSON($record, ['creator', 'modifier']);

			return parent::updateSet($record, $options);
		}

		public static function remove($id, $options = []) {
			txbegin();

			if ($record = static::get($id)) {
				static::update($record);
			}

			$ret = parent::remove($id, $options);

			txcommit();

			return $ret;
		}

		public static function removeSet($options = []) {
			txbegin();

			static::updateSet([], $options);

			$ret = parent::removeSet($options);

			txcommit();

			return $ret;
		}

		public static function getRecordLog($record, $options = []) {
			$query = sprintf('
				select
						row_to_json(log) as item
					from %s as log
						where
							log.id = $1
						order by recorddatetime
						',
				static::$container . 'log');

			$log = static::queryRows($query, [$record['id']]);

			foreach ($log as &$item) {
				$item = \JSON::parse($item['item']);
				static::fromJSON($item, ['creator', 'modifier']);
			}

			return $log;
		}

		public static function isEmptyField($record, $field) {
			if (in_array($field, ['creator', 'modifier'])) {
				return true;
			}

			return parent::isEmptyField($record, $field);
		}

		// Document behavior

		public static function loadDocumentLog(&$document) {
			if (!TrackableRecord::isTrackable(get_called_class())) {
				return;
			}

			$document['log'] = static::getRecordLog($document);

			foreach (static::$documentReference as $name => $ref) {
				if (isset($document[$name])) {
					/**
					 * @var TrackableRecord $class
					 */
					$class = $ref['record'];

					switch ($ref['type']) {
						case Relation::Many:
							foreach ($document[$name] as &$item) {
								$class::loadDocumentLog($item);
							}
							break;
						case Relation::One:
							$class::loadDocumentLog($document[$name]);
							break;
					}
				}
			}
		}

		public static function getDocumentHistory($document) {
			if (!TrackableRecord::isTrackable(get_called_class())) {
				return [];
			}

			$history = [
				'log' => static::getRecordLog($document)
			];

			foreach (static::$documentReference as $name => $ref) {
				if (isset($document[$name])) {
					/**
					 * @var TrackableRecord $class
					 */
					$class = $ref['record'];

					switch ($ref['type']) {
						case Relation::Many:
							$history[$name] = [];

							foreach ($document[$name] as &$item) {
								$history[$name][] = $class::getDocumentHistory($item);
							}
							break;
						case Relation::One:
							$history[$name] = $class::getDocumentHistory($document[$name]);
							break;
					}
				}
			}

			return $history;
		}
	}
}

 