<?php

namespace Data {
	class Result {
		private $connection = null;
		private $resource = null;

		public function __construct(Connection $connection, $resource) {
			$this->connection = $connection;
			$this->resource = $resource;
		}

		public function size() {
			return pg_num_rows($this->resource);
		}

		public function row() {
			$row = pg_fetch_assoc($this->resource);

			return $row ? $row : null;
		}

		public function rows() {
			$rows = pg_fetch_all($this->resource);

			return $rows ? $rows : [];
		}

		public function free() {
			return pg_free_result($this->resource);
		}

		public function affected() {
			return pg_affected_rows($this->resource);
		}
	}
}

 