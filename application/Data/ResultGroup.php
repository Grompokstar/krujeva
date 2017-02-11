<?php

namespace Data {
	class ResultGroup {
		/**
		 * @var Result[]
		 */
		private $results = [];

		private $current = 0;
		private $count = 0;

		/**
		 * @param Result[] $results
		 */
		public function __construct($results) {
			$this->results = $results;
			$this->count = count($results);
			$this->current = 0;
		}

		public function size() {
			$size = 0;

			foreach ($this->results as $result) {
				$size += $result->size();
			}

			return $size;
		}

		public function row() {
			$row = false;

			while ($this->current < $this->count && !$row) {
				if (!$row = $this->results[$this->current]->row()) {
					$this->current++;
				}
			}

			return $row;
		}

		public function rows() {
			$rows = [];

			foreach ($this->results as $result) {
				$rows = array_merge($rows, $result->rows());
			}

			return $rows;
		}

		public function free() {
			foreach ($this->results as $result) {
				$result->free();
			}
		}

		public function affected() {
			$affected = 0;

			foreach ($this->results as $result) {
				$affected += $result->affected();
			}

			return $affected;
		}
	}
}
