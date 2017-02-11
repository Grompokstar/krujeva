<?php

namespace Krujeva\Data {
	class TrackableRecord extends \Dict\Data\TrackableRecordFilter {

		public static function model() {

			return new static;
		}

		public static function referenceField(&$item, $field, $ref, $classreference, $referencefield) {

			if ($item[$ref] && !isset($item[$field]) && $reference = $classreference::get($item[$ref])) {
				$item[$field] = $reference[$referencefield];
			}
		}
	}
}

 