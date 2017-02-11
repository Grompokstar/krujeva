<?php

namespace Data {
	use System\Code;
	use System\Exception;

	class Connection {
		private $connection = null;
		private $txlevel = 0;
		private $connectionString = null;

		public function __construct($connectionString) {
			$this->connectionString = $connectionString;
		}

		public function connection() {
			return $this;
		}

		public function getConnection() {
			return $this->connection;
		}

		public function connect() {
			$this->connection = pg_connect($this->connectionString);

			if (!$this->connection) {
				throw new Exception('Postgresql connection failed', Code::ConnectionFailed);
			}

			pg_query($this->connection, "SET TIMEZONE TO 'UTC'");

			return $this->connection;
		}

		/**
		 * @param string $query
		 * @param array $args
		 * @param array $params
		 * @return Result
		 * @throws \System\Exception
		 */
		public function query($query, $args = [], $params = []) {
			if (!$this->connection) {
				$this->connect();
			}

			//$startTime = microtime(true);

			$resource = $args ? @pg_query_params($this->connection, $query, $args) : @pg_query($this->connection, $query);

			//if (!isset($params['noncalculate'])) {
				//@ ONLY DEV SERVER
				//\Analytic\Data\Query::set($query, $args, $startTime);
			//}

			if ($resource)  {
				$result = new Result($this, $resource);

				if (in_array('free', $params, true)) {
					$result->free();
				}

				return $result;
			} else {

				//var_dump($query);

				throw new Exception(pg_last_error($this->connection), Code::QueryFailed);
			}
		}

		public function queryRows($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$rows = $result->rows();
			$result->free();

			return $rows;
		}

		public function queryRow($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$row = $result->row();
			$result->free();

			return $row;
		}

		public function queryScalar($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$row = $result->row();
			$result->free();

			return $row ? array_shift($row) : null;
		}

		public function txbegin() {
			if (!$this->txlevel) {
				static::query('begin', [], ['free']);
			}

			$this->txlevel++;
		}

		public function txcommit($force = false) {
			if (!$this->txlevel) {
				return;
			}

			$this->txlevel--;

			if (!$this->txlevel || $force) {
				static::query('commit', [], ['free']);

				$this->txlevel = 0;
			}
		}

		public function txabort() {
			if ($this->txlevel) {
				static::query('rollback', [], ['free']);

				$this->txlevel = 0;
			}
		}

		public function txlevel() {
			return $this->txlevel;
		}

		public function close($tx = 'abort') {
			if ($this->connection) {
				if ($this->txlevel) {
					switch ($tx) {
						case 'abort':
							$this->txabort();
							break;
						case 'commit':
							$this->txcommit(true);
							break;
					}
				}

				pg_close($this->connection);

				$this->connection = null;
			}
		}
	}
}

 