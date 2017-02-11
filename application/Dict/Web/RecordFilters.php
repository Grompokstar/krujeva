<?php

namespace Dict\Web {
	use Data\Query;
	use Dict\RecordFilterType;
	use Security\Data\Roles;
	use Security\Data\Users;
	use Web\DataController;

	class RecordFilters extends DataController {
		protected $record = 'Dict\Data\RecordFilters';

		protected $securityKey = 'Dict.RecordFilters';

		protected static $__actions = ['item', 'insert', 'page', 'remove', 'records'];

		protected $subjectid;

		protected $subjecttype;

		public function page() {

			$this->response = [
				'count' => $this->countitems($this->pageOptions['count']),
				'items' => $this->items($this->pageOptions['items'])
			];

			$this->sendResponse();
		}

		public function records() {

			$class = $this->record;

			$this->response = $class::records();

			$this->sendResponse();
		}

		private function countitems($options = []) {
			$options = array_merge($options, ['fields' => 'distinct on (subjectid, subjecttype) subjectid, subjecttype']);

			$query = new Query('dict.recordfilters', $options);

			$query = new Query('(' . $query->select() . ') m');
			$query->fields("count(1) as count");

			return queryScalar($query->select());
		}

		private function items($options = []) {
			$class = $this->record;

			$options = array_merge($options, ['fields' => 'distinct on (subjectid, subjecttype) id, subjectid, subjecttype']);

			$items = $class::select($options);

			foreach ($items as &$item) {
				$item['subjectname'] = $class::findSubjectName($item['subjecttype'], $item['subjectid']);
			}

			return $items;
		}

		public function doInsert() {
			$class = $this->record;

			$subjecttype = $this->item['item']['subjecttype'];

			$subjectid = $this->item['item']['subjectid'];

			if (!$subjecttype || !$subjectid) {
				$this->sendResponse();
				return;
			}

			$items = [];

			foreach ($this->item['records'] as $record) {
				foreach ($record['relations'] as $relation) {

					if (!isset($relation['data'])) {
						continue;
					}

					$item = [
						'subjectid' => $subjectid,
						'subjecttype' => $subjecttype,
						'record' => $record['record'],
						'relationclass' => $relation['record'],
						'relationalias' => $relation['alias'],
						'include' => array_column($relation['data']['include'], 'id'),
						'exclude' => array_column($relation['data']['exclude'], 'id'),
						'options' => $relation['data']['options']
					];

					if (count($item['options']) || count($item['include']) || count($item['exclude'])) {
						$items[] = $item;
					}
				}
			}

			//remove
			$class::removeSet([
				'where' => 'subjecttype = $1 and subjectid = $2',
				'data' => [$subjecttype, $subjectid]
			]);

			$this->response = $class::insertItems($items);

			$this->sendResponse();
		}

		public function item() {
			$this->bind('item');

			if (!$this->item['subjectid'] || !$this->item['subjecttype']) {
				$this->sendResponse();
				return;
			}

			$class = $this->record;

			$this->response = $class::items($this->item['subjecttype'], $this->item['subjectid']);

			$this->sendResponse();
		}

		public function remove() {
			$this->bind('item');

			if (!$this->item['subjectid'] || !$this->item['subjecttype']) {
				$this->sendResponse();
				return;
			}

			$class = $this->record;

			$this->response = $class::removeSet([
				'where' => 'subjecttype = $1 and subjectid = $2',
				'data' => [
					$this->item['subjecttype'],
					$this->item['subjectid']
				]
			]);

			$this->sendResponse();
		}
	}
}
 