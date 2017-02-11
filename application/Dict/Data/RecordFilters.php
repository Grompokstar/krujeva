<?php

namespace Dict\Data {
	use Data\Query;
	use Dict\RecordCollector;
	use Dict\RecordFilterType;

	class RecordFilters extends Record {
		public static $container = 'dict.recordfilters';

		public static $fields = [
			'id' => ['int'],
			'subjectid' => ['int'],
			'subjecttype' => ['int'],
			'record' => ['string'],
			'relationclass' => ['string'],
			'relationalias' => ['string'],
			'include' => ['string'],
			'exclude' => ['string'],
			'options' => ['string']
		];

		public static function records() {
			return RecordCollector::records();
		}

		public static function insertItems($items) {
			$res = [];

			txbegin();

			foreach ($items as $item) {
				$item['options'] = \JSON::stringify($item['options']);
				$item['include'] = pgIntArrayEncode($item['include']);
				$item['exclude'] = pgIntArrayEncode($item['exclude']);

				$res[] = static::insert($item);
			}

			txcommit();

			return $res;
		}

		public static function findSubjectName($subjecttype, $subjectid) {

			switch ($subjecttype) {
				case RecordFilterType::Role:
					$subject =  \Security\Data\Roles::get($subjectid);
					$field = 'name';
					return $subject[$field];

				case RecordFilterType::User:
					$subject = \Security\Data\Users::get($subjectid);
					$field = 'login';
					return $subject[$field];
			}

			return null;
		}

		public static function items($subjecttype, $subjectid) {

			$result = [
				'id' => '123456',
				'subjecttype' => $subjecttype,
				'subjectid' => $subjectid,
				'records' => '',
				'subjectname' => static::findSubjectName($subjecttype, $subjectid)
			];

			$items = static::select([
				'where' => 'subjecttype = $1 and subjectid = $2',
				'data' => [$subjecttype, $subjectid]
			]);

			$records = static::records();

			foreach ($items as $item) {

				if (!isset($records[$item['record']])) {
					continue;
				}

				if (!isset($records[$item['record']]['relations'][$item['relationalias']])) {
					continue;
				}

				$item['include'] = pgIntArrayDecode($item['include']);
				$item['exclude'] = pgIntArrayDecode($item['exclude']);

				$include = [];
				$exclude = [];

				foreach (['include', 'exclude'] as $includetype) {
					if (!count($item[$includetype])) {
						continue;
					}

					$relationClass = $item['relationclass'];

					$relationTable = explode('.', $relationClass::$container);

					$field = $records[$item['record']]['relations'][$item['relationalias']]['columns'][0];

					$relationItems = $relationClass::select([
						'fields' => $relationTable[1] . '.id, ' . $relationTable[1] . '.' . $field,
						'where' => $relationTable[1] . '.id in (' . implode(',', $item[$includetype]) . ')'
					]);

					foreach ($relationItems as $relationItem) {

						$data = [
							'id' => $relationItem['id'],
							'name' => $relationItem[$field],
							'type' => $includetype
						];

						if ($includetype == 'include') {
							$include[] = $data;
						} else {
							$exclude[] = $data;
						}
					}
				}

				$records[$item['record']]['relations'][$item['relationalias']]['data'] = [
					'options' => \JSON::parse($item['options']),
					'include' => $include,
					'exclude' => $exclude
				];
			}

			$result['records'] = $records;

			return $result;
		}

		private static function findRules($record, $context = null) {
			$query = new Query(static::$container);
			$query->where('(subjecttype=$1 and subjectid=$2)', [RecordFilterType::Role, context('role.id', $context)]);
			$query->where('(subjecttype=$1 and subjectid=$2)', [RecordFilterType::User, context('user.id', $context)]);

			$items = static::select($query->options(null, 'or'));

			$records = static::records();

			$container = $record::$container;

			$recordFilters = $record::$recordFilters;

			$recordData = $records[$container];

			$recordData['relations'] = [];

			foreach ($items as $item) {

				if ($item['record'] !== $container) {
					continue;
				}

				if (!isset($records[$item['record']])) {
					continue;
				}

				if (!isset($records[$item['record']]['relations'][$item['relationalias']])) {
					continue;
				}

				$relation = $records[$item['record']]['relations'][$item['relationalias']];

				$relation['data'] = [
					'options' => \JSON::parse($item['options']),
					'include' => pgIntArrayDecode($item['include']),
					'exclude' => pgIntArrayDecode($item['exclude'])
				];

				$recordData['relations'][$relation['alias']] = $relation;
			}

			//#Find by DefaultFunction
			if (!count($recordData['relations']) && method_exists($record, 'defaultRecordFilter')) {

				$recordFilters['default'] = $record::defaultRecordFilter($context);

			}

			//#Find Default
			if (!count($recordData['relations']) && isset($recordFilters['default']) && count($recordFilters['default'])) {

				foreach ($recordFilters['default'] as $relationalias => $relationdata) {

					if (!isset($records[$container]['relations'][$relationalias])) {
						continue;
					}

					$relation = $records[$container]['relations'][$relationalias];

					$relation['data'] = $relationdata;

					$recordData['relations'][$relation['alias']] = $relation;
				}
			}

			return $recordData;
		}

		public static function userFilter($record, &$options = [], $filter = []) {

			//#Add filter conditions
			if (count($filter)) {

				$filter = new \Data\UserFilter($record, $options, $filter);

				$options = $filter->options();
			}
		}

		public static function formFilter($record, &$options = [], $filter = [], $context = null) {

			if ((context('role.name', $context) != "root" || isset($options['unRoot']) ) && isset($record::$recordFilters)) {

				//#Find rules
				$recordData = static::findRules($record, $context);

				if (count($recordData['relations'])) {

					$query = new \Dict\DictFilter($recordData, $context, $options);

					$options = $query->options();
				}
			}

			//#Add filter conditions
			if (count($filter)) {

				$filter = new \Data\UserFilter($record, $options, $filter);

				$options = $filter->options();
			}
		}
	}
}
