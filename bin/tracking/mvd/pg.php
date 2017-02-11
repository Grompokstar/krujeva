<?php

namespace splib\data {
	class pg {
		private $params = [];
		private $connections = [];
		private $default = 0;
		private $result = [];

		public function __construct($params) {
			if (!is_array($params)) {
				$params = [$params];
			}

			$this->params = $params;
		}

		public function connect($server = 0) {
			$this->verifyParam($server);

			if (!$this->checkConnection($server)) {
				$connection = @pg_connect($this->params[$server]);

				if (!$connection) {
					throw new \Exception("Connection $server failed");
				}

				$this->connections[$server] = $connection;
			}
		}

		public function close() {
			foreach ($this->connections as $server => $connection) {
				@pg_close($connection);
			}

			$this->connections = [];
		}

		public function query($query, $args = [], $server = null) {
			if ($server === null) {
				$server = $this->default;
			}

			$servers = $server;

			if (!is_array($servers)) {
				$servers = [$servers];
			}

			$this->result = [];

			foreach ($servers as $server) {
				$this->connect($server);

				if ($args) {
					$this->result[$server] = @pg_query_params($this->connections[$server], $query, $args);
				} else {
					$this->result[$server] = @pg_query($this->connections[$server], $query);
				}

				if ($this->result[$server] === false) {
					$message = pg_last_error($this->connections[$server]);

					throw new \Exception("Query failed on $server ($message)");
				}
			}
		}

		public function queryRows($query, $args = [], $server = null) {
			$rows = [];

			$this->query($query, $args, $server);

			foreach ($this->result as $server => $result) {
				$resultRows = pg_fetch_all($result);

				if (is_array($resultRows)) {
					$rows = array_merge($rows, $resultRows);
				}
			}

			$this->result = [];

			return $rows;
		}

		public function queryRow($query, $args = [], $server = null) {
			$this->query($query, $args, $server);
			$row = $this->next();
			$this->free();

			return $row;
		}

		public function queryScalar($query, $args = [], $server = null) {
			$row = $this->queryRow($query, $args, $server);

			if (is_array($row)) {
				return array_shift($row);
			}

			return false;
		}

		public function next() {
			if ($this->result) {
				foreach ($this->result as $server => $result) {
					$row = pg_fetch_assoc($result);

					if ($row === false) {
						unset($this->result[$server]);
					} else {
						return $row;
					}
				}
			}

			$this->result = [];

			return false;
		}

		public function free() {
			foreach ($this->result as $server => $result) {
				pg_free_result($result);
			}

			$this->result = [];
		}

		private function verifyParam($server = 0) {
			if (!isset($this->params[$server])) {
				throw new \Exception("Param $server does not exist");
			}
		}

		private function checkConnection($server = 0) {
			if (!isset($this->connections[$server]) || !$this->connections[$server]) {
				return false;
			}

			return true;
		}
	}
}
 