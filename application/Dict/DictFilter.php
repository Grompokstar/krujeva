<?php

namespace Dict {

	class DictFilter extends \Data\Query {
		public $record;
		public $relation;
		public $options = [];
		public $arg = [];

		public $includeOR = [];
		public $includeAND = [];
		public $exclude = [];

		public $context;

		public function __construct($recordData = [], $context = null, $initial = []) {
			$this->record = $recordData;
			$this->context = $context;

			return parent::__construct($recordData['record'], $initial);
		}

		public function init() {
			parent::init();

			foreach ($this->record['relations'] as $relation) {
				$this->relation = $relation;
				$this->relation();
			}

			$where = [];

			if (count($this->includeAND)) {
				$where[] = '(' . implode(' and ', $this->includeAND) . ')';
			}

			if (count($this->includeOR) > 1) {
				$where[] = '(' . implode(' or ', $this->includeOR) . ')';
			}

			$where = implode(' and ', $where);

			if (count($this->includeOR) == 1) {
				$where .= ($where ? 'or ' : '') . '(' . implode(' or ', $this->includeOR) . ')';
			}

			if (count($this->exclude)) {
				$where .= ($where ? 'and ' : '') .'(' . implode(' and ', $this->exclude) . ')';
			}

			if ($where) {
				$this->where($where);
			}
		}

		public function joinTable() {
			$relation = $this->relation;

			$class = $relation['record'];

			$relationTable  = explode('.', $class::$container);

			$table = explode('.', $this->record['record']);

			$alias = explode('.', $relation['alias']);

			$recordClass = $this->record['recordClass'];

			if (!isset($this->options['fields'])) {
				$this->fields($table[1] . '.*');

				if (isset($recordClass::$fields['geog'])) {
					$this->fields('st_asgeojson('. $table[1] .'.geog) geog', true);
				}
			}

			$jointable = $table[1];

			if ($jointable !== $relationTable[1]) {

				$on = $relation['rel']['field'] . ' = ' . $relation['rel']['ref'];

				$this->join($class::$container, $alias[1], $on, 'inner');
			}
		}

		public function branchCondition() {
			$relation = $this->relation;

			if (!isset($relation['data'])) {
				return;
			}

			if (!empty($relation['data']['exclude']) && !empty($relation['data']['exclude'])) {
				return;
			}

			$this->joinTable();

			$class = $relation['record'];

			$alias = explode('.', $relation['alias']);

			$arg = $this->arg++;

			$condition = "(" . $alias[1] . ".path ? array(select ('*.' || id || '.*')::lquery from unnest(" . pgArg($arg) . "::int[]) as id))";

			//@include condition
			if (isset($relation['data']['include']) && count($relation['data']['include'])) {
				$this->addCondition($condition);

				$this->data(pgIntArrayEncode($relation['data']['include']), true);

				$this->relation['data']['include'] = [];
			}

			//@exclude condition
			if (isset($relation['data']['exclude']) && count($relation['data']['exclude'])) {

				$this->exclude[] = 'not ' . $condition;

				$this->data(pgIntArrayEncode($relation['data']['exclude']), true);

				$this->relation['data']['exclude'] = [];
			}
		}

		public function addInclude($ids = []) {

			if (!isset($this->relation['data'])) {
				$this->relation['data'] = [];
			}

			if(!isset($this->relation['data']['include'])) {
				$this->relation['data']['include'] = [];
			}

			$this->relation['data']['include'] = array_merge($this->relation['data']['include'], $ids);
		}

		private function relation() {
			$relation = $this->relation;

			if (!isset($relation['data'])) {
				return;
			}

			/*
			 * join
			 */
			if (isset($relation['join']) && is_array($relation['join'])) {

				foreach ($relation['join'] as $join) {

					$join['type'] = isset($join['type']) ? $join['type'] : 'inner';

					$relationTable = explode('.', $join['table']);

					if ($join['table'] !== $this->table) {

						$this->join($join['table'], $relationTable[1], $join['on'], $join['type']);

					}
				}
			}

			/*
			 * Options = call Callback
			 */
			if (isset($relation['data']['options']) && count($relation['data']['options'])) {
				call_user_func([$relation['record'], 'formRecordFilterOptions'], [&$relation], [&$this]);

				$relation = $this->relation;
			}


			/*
			 *  include
			 */
			if (isset($relation['data']['include']) &&  count($relation['data']['include'])) {

				$condition = null;

				switch ($this->getFieldType()) {
					case "array":
						$arg = $this->arg++;
						$condition = $relation['rel']['field'] . ' && ' . pgArg($arg) . '::int[]';
						break;

					case "int":
						$arg = $this->arg++;
						$condition = $relation['rel']['field'] . ' = any (' . pgArg($arg) . ')';
						break;
				}

				if ($condition) {

					$this->addCondition($condition);

					$this->data(pgIntArrayEncode($relation['data']['include']), true);
				}
			}

			/*
			 *  exclude
			 */
			if (isset($relation['data']['exclude']) && count($relation['data']['exclude'])) {

				$condition = null;

				switch ($this->getFieldType()) {
					case "array":
						$arg = $this->arg++;
						$condition = 'not (' . $relation['rel']['field'] . ' && ' . pgArg($arg) . '::int[])';
						break;

					case "int":
						$arg = $this->arg++;
						$condition = $relation['rel']['field'] . ' <> all (' . pgArg($arg) . ')';
						break;
				}

				if ($condition) {
					$this->exclude[] = $condition;

					$this->data(pgIntArrayEncode($relation['data']['exclude']), true);
				}
			}
		}

		private function getFieldType () {
			$relation = $this->relation;
			return isset($relation['type']) ? $relation['type'] : 'int';
		}

		public function addCondition($condition, $implodetype = null) {

			if ($implodetype === null) {
				$implodetype = RecordFilterImplodeType::OrType;
			}

			if ($implodetype == RecordFilterImplodeType::AndType) {
				$this->includeAND[] = $condition;
			} else {
				$this->includeOR[] = $condition;
			}
		}

	}
}
