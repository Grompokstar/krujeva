<?php

namespace Trackers\Web {
	use Data\Query;
	use Web\DataController;

	class Trackers extends DataController {
		protected $record = 'Trackers\Data\Trackers';

		protected static $__actions = ['get', 'find', 'page', 'insert', 'update', 'remove', 'findBy'];

		private $selectOptions = [
			'fields' => 'trackers.*, types.name as typename, vendors.name as vendorname',
			'join' => [
				[
					'table' => 'trackers.types',
					'on' => 'types.id = trackers.typeid'
				],
				[
					'table' => 'trackers.vendors',
					'on' => 'vendors.id = trackers.vendorid',
					'type' => 'left'
				]
			]
		];

		protected function preGet() {

			parent::preGet();

			$this->getOptions = array_merge($this->getOptions, $this->selectOptions);
		}

		protected function doGet() {

			parent::doGet();

			\Trackers\Data\Trackers::build($this->response['item']);
		}

		protected function prePage() {

			parent::prePage();

			$this->pageOptions['items'] = array_merge($this->pageOptions['items'], $this->selectOptions, ['order' => 'num']);

			$this->pageOptions['count'] = array_merge($this->pageOptions['count'], $this->selectOptions);

			$this->addFilter($this->pageOptions['items']);
			$this->addFilter($this->pageOptions['count']);
		}

		protected function doPage() {

			parent::doPage();

			\Trackers\Data\Trackers::build($this->response['items'], ['array']);
		}

		protected function preFind() {

			parent::preFind();

			$this->findOptions = array_merge($this->findOptions, $this->selectOptions, ['order' => 'num']);

			$this->addFilter($this->findOptions);
		}

		protected function doFind() {

			parent::doFind();

			\Trackers\Data\Trackers::build($this->response['items'], ['array']);
		}

		protected function doInsert() {

			$this->encode($this->item);

			parent::doInsert();

			\Trackers\Data\Trackers::build($this->response['item']);
		}

		protected function doUpdate() {

			$this->encode($this->item);

			parent::doUpdate();

			\Trackers\Data\Trackers::build($this->response['item']);
		}

		private function encode(&$tracker) {

			if (isset($tracker['systems']) && is_array($tracker['systems']) && $tracker['systems'] && isset($tracker['systems'][0]['name'])) {
				$tracker['systems'] = array_column($tracker['systems'], 'id');
			}
		}

		private function addFilter(&$options) {

			$filter = isset($this->options['filter']) ? $this->options['filter'] : [];

			if (isset($this->options['pattern'])) {
				$filter['num'] = $this->options['pattern'];
			}

			if (isset($this->options['autocomplete'])) {
				$filter['autocomplete'] = true;
			}

			$filter = array_merge(['num' => null, 'typeid' => null, 'autocomplete' => false], $filter);

			$query = new Query(null, $options);

			if ($filter['num']) {
				$query->where("(lower(num) like '%' || $1 || '%')", [mb_strtolower($filter['num'])]);
			}

			if ($filter['typeid']) {
				$query->where('typeid = $1', [$filter['typeid']]);
			}

			if ($filter['autocomplete']) {
				$query->limit(20);
			}

			$options = $query->options();

			return true;
		}

		protected function findBy() {

			$class = $this->record;

			$this->bind('options');

			$this->findOptions = ['adjust'];

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(num) like $1';
				$this->findOptions['data'] = ['%' . mb_strtolower($this->options['pattern']) . '%'];

				if (isset($this->options['typeid']) && $this->options['typeid']) {
					$this->findOptions['where'] .= ' and typeid = $2';
					$this->findOptions['data'][] = $this->options['typeid'];
				}

				if (isset($this->options['autocomplete']) && $this->options['autocomplete']) {
					$this->findOptions['limit'] = 20;
				}
			}

			$this->response = ['items' => $class::select($this->findOptions)];

			$this->sendResponse();
		}
	}
}
 