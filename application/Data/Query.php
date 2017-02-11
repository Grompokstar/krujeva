<?php

namespace Data {
	class Query {
		protected  $table;
		protected $initial;
		protected $options = [];
		protected $arg;
		protected $actualData;

		public function __construct($table = null, $initial = []) {
			$this->table = $table;
			$this->initial = $initial;

			$this->init();
		}

		public function init() {
			$this->options = [];

			$this->options['data'] = [];
			$this->options['values'] = [];
			$this->options['fields'] = null;
			$this->options['join'] = [];
			$this->options['where'] = [];
			$this->options['group'] = null;
			$this->options['order'] = null;
			$this->options['offset'] = null;
			$this->options['limit'] = null;
			$this->options['return'] = null;
			$this->options['convert'] = [];

			$this->actualData = null;

			$initial = $this->initial;

			if ($initial && is_array($initial)) {
				if (isset($initial['data']) && $initial['data']) {
					$this->options['data'] = array_merge($this->options['data'] , $initial['data']);
				}

				if (isset($initial['values']) && $initial['values']) {
					$this->options['values'] = array_merge($this->options['values'] , $initial['values']);
				}

				if (isset($initial['fields'])) {
					$this->options['fields'] = $initial['fields'];
				}

				if (isset($initial['join']) && $initial['join']) {
					$this->options['join'] = array_merge($this->options['join'], $initial['join']);
				}

				if (isset($initial['where']) && $initial['where']) {
					$this->options['where'][] = '(' . $initial['where'] . ')';
				}

				if (isset($initial['group'])) {
					$this->options['group'] = $initial['group'];
				}

				if (isset($initial['order'])) {
					$this->options['order'] = $initial['order'];
				}

				if (isset($initial['offset'])) {
					$this->options['offset'] = $initial['offset'];
				}

				if (isset($initial['limit'])) {
					$this->options['limit'] = $initial['limit'];
				}

				if (isset($initial['return'])) {
					$this->options['return'] = $initial['return'];
				}

				if (isset($initial['convert'])) {
					$this->options['convert'] = $initial['convert'];
				}

				if (isset($initial['result'])) {
					$this->options['result'] = $initial['result'];
				}

				if (in_array('forupdate', $initial)) {
					$this->options[] = 'forupdate';
				}
			}

			$this->arg = count($this->options['data']);

			return $this;
		}

		public function table($table) {
			$this->table = $table;
		}

		/**
		 * @param null $data
		 * @param bool $append
		 * @return Query|array
		 */
		public function data($data = null, $append = false) {
			if ($data === null) {
				return $this->actualData === null ? $this->options['data'] : $this->actualData;
			}

			if ($append) {
				if (!is_array($data)) {
					$data = [$data];
				}

				$this->options['data'] = array_merge($this->options['data'], $data);
			} else {
				$this->options['data'] = $data;
			}

			return $this;
		}

		public function values($values) {
			$this->options['values'] = $values;
		}

		public function fields($fields, $append = false) {
			if ($append && $this->options['fields']) {
				$this->options['fields'] .= ', ' . $fields;
			} else {
				$this->options['fields'] = $fields;
			}

			return $this;
		}

		public function join($table, $as, $on, $type = null) {

			if (isset($this->options['join']) && is_array($this->options['join'])) {

				foreach($this->options['join'] as $join) {

					if ($join['table'] == $table && $join['as'] == $as) {
						return $this;
					}

				}

			}

			$this->options['join'][] = [
				'table' => $table,
				'as' => $as,
				'on' => $on,
				'type' => $type
			];

			return $this;
		}

		public function where($condition, $data = [], &$tr = []) {
			$count = count($data);
			$tr = [];

			for ($i = $count; $i >= 1; $i--) {
				$from = '$' . $i;
				$to = '$' . ($this->arg + $i);
				$condition = str_replace($from, $to, $condition);

				$tr[$from] = $to;
			}

			$this->arg += $count;

			$this->options['where'][] = '(' . $condition . ')';
			$this->options['data'] = array_merge($this->options['data'], $data);

			return $this;
		}

		public function group($group) {
			$this->options['group'] = $group;

			return $this;
		}

		public function order($order) {
			$this->options['order'] = $order;

			return $this;
		}

		public function offset($offset) {
			$this->options['offset'] = $offset;

			return $this;
		}

		public function result($result) {

			$this->options['result'] = $result;

			return $this;
		}

		public function limit($limit) {
			$this->options['limit'] = $limit;

			return $this;
		}

		public function returning($returning) {
			$this->options['return'] = $returning;

			return $this;
		}

		public function convert($convert) {
			$this->options['convert'] = $convert;

			return $this;
		}

		public function forUpdate($forUpdate = true) {
			if ($forUpdate) {
				$this->options[] = 'forupdate';
			} else {
				$this->options = array_diff($this->options, ['forupdate']);
			}

			return $this;
		}

		/**
		 * @param array|null $options
		 * @return Query|array
		 */
		public function options($options = null, $whereglue = 'and') {
			if ($options === null) {
				$options = $this->options;

				if ($options['where']) {
					$options['where'] = implode(' '. $whereglue .' ', $options['where']);
				} else {
					unset($options['where']);
				}

				return $options;
			} else if (is_array($options)) {
				if (isset($options['values'])) {
					$this->values($options['values']);
				}

				$argTR = [];

				if (isset($options['where']) && $options['where']) {
					$this->where($options['where'], isset($options['data']) ? $options['data'] : [], $argTR);
				}

				if (isset($options['fields'])) {
					$this->fields(strtr($options['fields'], $argTR));
				}

				if (isset($options['join'])) {
					foreach ($options['join'] as $join) {
						$this->join($join['table'], option('as', $join), strtr($join['on'], $argTR), option('type', $join));
					}
				}

				if (isset($options['group'])) {
					$this->group($options['group']);
				}

				if (isset($options['order'])) {
					$this->order($options['order']);
				}

				if (isset($options['limit'])) {
					$this->limit($options['limit']);
				}

				if (isset($options['offset'])) {
					$this->offset($options['offset']);
				}

				if (isset($options['return'])) {
					$this->returning($options['return']);
				}

				if (isset($options['convert'])) {
					$this->convert($options['convert']);
				}
			}

			return $this;
		}

		public function select() {
			return Build::select($this->table, $this->options());
		}

		public function insert() {
			list ($query, $data) = Build::insert($this->table, $this->options());
			$this->actualData = $data;

			return $query;
		}

		public function update() {
			list ($query, $data) = Build::update($this->table, $this->options());
			$this->actualData = $data;

			return $query;
		}

		public function remove() {
			return Build::remove($this->table, $this->options());
		}
	}
}
