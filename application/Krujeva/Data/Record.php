<?php

namespace Krujeva\Data {
	class Record extends \Dict\Data\RecordFilter {

		public static function model() {
			return new static;
		}

		public static function referenceField(&$item, $field, $ref, $classreference, $referencefield) {

			if (isset($item[$ref]) && $item[$ref] && !isset($item[$field]) && $reference = $classreference::get($item[$ref])) {
				$item[$field] = $reference[$referencefield];
			}
		}

	}
}

 