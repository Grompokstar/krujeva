<?php

namespace Dict\Data {
	class TrackableRecordFilter extends \Data\TrackableRecord {

		public static function recordFilter(&$options = [], $filter = [], $context = null) {
			RecordFilters::formFilter(get_called_class(), $options, $filter, $context);
		}

		public static function formRecordFilterOptions(&$relation, &$query) {
			$relation = $relation[0];
			$query = $query[0];
		}

	}
}
 