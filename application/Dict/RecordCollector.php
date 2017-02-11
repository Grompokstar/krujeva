<?php

namespace Dict {

	class RecordCollector {

		private static $records = [];

		public static function addRecord($recordClass, $name) {

			if (!isset($recordClass::$recordFilters)) {
				return;
			}

			$record = $recordClass::$container;

			if (!isset(static::$records[$record])) {

				static::$records[$record] = [
					'record' => $record,
					'recordClass' => $recordClass,
					'name' => $name,
					'relations' => [],
				];
			}

			foreach ($recordClass::$recordFilters as $relationalias => &$relationdata) {

				if ($relationalias === 'default') {
					continue;
				}

				$relationClass = $relationdata['record'];

				$options = isset($relationClass::$recordFilterOptions) ? $relationClass::$recordFilterOptions : [];

				static::$records[$record]['relations'][$relationalias] = array_merge($relationdata, [
					'alias' => $relationalias,
					'options' => $options
				]);
			}
		}

		public static function records() {
			return static::$records;
		}
	}
}