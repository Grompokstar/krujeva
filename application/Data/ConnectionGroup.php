<?php

namespace Data {
	class ConnectionGroup {
		/**
		 * @var Connection[]
		 */
		private $connections = [];
		private $pool = [];
		private $poolSize = 0;
		private $default = null;

		private $txPool = null;

		public function __construct($connectionStrings) {
			$this->poolSize = count($connectionStrings);

			$poolIndex = 0;

			foreach ($connectionStrings as $connectionString) {
				$this->connections[] = new Connection($connectionString);
				$this->pool[] = $poolIndex++;
			}
		}

		public function connection($index) {
			return $this->connections[$index];
		}

		public function connect() {
			return $this->connections;
		}

		public function poolSize() {
			return $this->poolSize;
		}

		public function setDefault($default) {
			if ($default === null || in_array($default, $this->pool)) {
				$this->default = $default;
			}
		}

		/**
		 * @param string $query
		 * @param array $args
		 * @param array $params
		 * @return ResultGroup
		 * @throws \System\Exception
		 */
		public function query($query, $args = [], $params = []) {
			$pool = $this->getQueryPool($params);

			$results = [];

			foreach ($pool as $server) {
				$connection = $this->connections[$server];

				$results[] = $connection->query($query, $args, $params);
			}

			$result = new ResultGroup($results);

			if (in_array('free', $params, true)) {
				$result->free();
			}

			return $result;
		}

		/**
		 * @param string $query
		 * @param array $args
		 * @param array $params
		 * @return array
		 */
		public function queryRows($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$rows = $result->rows();
			$result->free();

			return $rows;
		}

		/**
		 * @param string $query
		 * @param array $args
		 * @param array $params
		 * @return array|bool
		 */
		public function queryRow($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$row = $result->row();
			$result->free();

			return $row;
		}

		/**
		 * @param string $query
		 * @param array $args
		 * @param array $params
		 * @return mixed|null
		 */
		public function queryScalar($query, $args = [], $params = []) {
			$result = static::query($query, $args, $params);
			$row = $result->row();
			$result->free();

			return $row ? array_shift($row) : null;
		}

		public function txbegin($pool = null) {
			$pool = $this->getTxPool($pool, true);

			foreach ($pool as $server) {
				$this->connections[$server]->txbegin();
			}
		}

		public function txcommit() {
			if (!$pool = $this->getTxPool()) {
				return;
			}

			$txlevel = null;

			foreach ($pool as $server) {
				$this->connections[$server]->txcommit();

				if ($txlevel === null) {
					$txlevel = $this->connections[$server]->txlevel();
				}
			}

			if (!$txlevel) {
				$this->txPool = null;
			}
		}

		public function txabort() {
			if (!$pool = $this->getTxPool()) {
				return;
			}

			foreach ($pool as $server) {
				$this->connections[$server]->txabort();
			}

			$this->txPool = null;
		}

		public function close($tx = 'abort') {
			foreach ($this->connections as $connection) {
				$connection->close($tx);
			}
		}

		protected function getQueryPool($params) {
			$pool = isset($params['server']) ? $params['server'] : null;

			if ($pool === null) {
				$pool = $this->default === null ? [rand(0, $this->poolSize - 1)] : [$this->default];
			} else if (!is_array($pool)) {
				$pool = $pool === true ? $this->pool : [$pool%$this->poolSize];
			}

			return $pool;
		}

		protected function getTxPool($pool = null, $create = false) {
			if ($this->txPool === null) {
				if ($pool === null) {
					if (!$create) {
						return null;
					}

					$pool = $this->default === null ? [rand(0, $this->poolSize - 1)] : [$this->default];
				} else if (!is_array($pool)) {
					$pool = $pool === true ? $this->pool : [$pool%$this->poolSize];
				}
			} else {
				$pool = $this->txPool;
			}

			$this->txPool = $pool;

			return $this->txPool;
		}
	}
}

 