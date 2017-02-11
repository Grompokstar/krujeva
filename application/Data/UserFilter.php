<?php

namespace Data {

	class UserFilter extends \Data\Query  {
		protected $record;
		public $filter;

		public function __construct($record, $options = [], $filter = []) {
			$this->record = $record;
			$this->filter = $filter;

			return parent::__construct($record::$container, $options);
		}

		public function init() {
			parent::init();

			$record = $this->record;

			if (!isset($record::$fields)) {
				return;
			}

			$table = explode('.', $this->table);

			$filter = ['autocomplete' => false];

			$fields = array_keys($record::$fields);

			$filterfields = array_keys($record::$filter);

			foreach ($record::$fields as $field => $fieldtype) {
				$filter[$field] = null;
			}

			foreach ($this->filter as $field => $fieldvalue) {
				if (in_array($field, $fields) || in_array($field, $filterfields) || $field === "autocomplete") {
					$filter[$field] = $fieldvalue;
				}
			}

			foreach ($filter as $field => $fieldvalue) {

				if (is_bool($fieldvalue) || is_array($fieldvalue)) {

				} else {
					$fieldvalue = trim($fieldvalue);
				}

				if ($field == 'autocomplete') {
					if ($fieldvalue) {
						$this->limit(7);
					}

					continue;
				}

				if ($fieldvalue === null || $fieldvalue == '') {
					continue;
				}

				if (isset($record::$filter) && isset($record::$filter[$field])) {

					if (is_string($record::$filter[$field])) {

						$this->where($record::$filter[$field], [$fieldvalue]);

					} else if (is_array($record::$filter[$field]) && isset($record::$filter[$field]['callback'])) {

						call_user_func([$record, $record::$filter[$field]['callback']], $field, $fieldvalue, [&$this]);

					}

				} else {
					$this->where($table[1] . '.' . $field . ' = $1', [$fieldvalue]);
				}
			}
		}
	}

}