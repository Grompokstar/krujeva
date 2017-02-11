<?php

namespace Dict\Data {
	class RecordFilter extends \Data\Record {

		public static function model() {
			return new static;
		}

		public static function recordFilter(&$options = [], $filter = [], $context = null) {
			RecordFilters::formFilter(get_called_class(), $options, $filter, $context);
		}

		public static function userFilter(&$options = [], $filter = []) {
			RecordFilters::userFilter(get_called_class(), $options, $filter);
		}

		public static function formRecordFilterOptions(&$relation, &$query) {
			$relation = $relation[0];
			$query = $query[0];
		}
	}
}
 