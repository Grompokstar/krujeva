<?php

namespace Web {
	use Security\AccessMode;

	abstract class DataController extends Controller {
		public $__xhr = true;

		protected static $__actions = ['get', 'find', 'page', 'insert', 'update', 'remove'];

		protected $securityKey;

		/**
		 * @var \Data\Record
		 */
		protected $record;

		protected $id;

		protected $offset;
		protected $limit;
		protected $options;

		protected $item;

		protected $defaults = [
			'limit' => 10
		];

		protected $response = null;

		protected $getOptions = [];
		protected $pageOptions = ['count' => [], 'items' => []];
		protected $findOptions = [];
		protected $insertOptions = [];
		protected $updateOptions = [];
		protected $removeOptions = [];

		public function init($options = []) {

			if ($this->securityKey) {
				switch (strtolower($options['action'])) {
					case 'get':
					case 'page':
					case 'find':
						verify($this->securityKey, AccessMode::Read);
						break;
					case 'insert':
						verify($this->securityKey, AccessMode::Insert);
						break;
					case 'update':
						verify($this->securityKey, AccessMode::Update);
						break;
					case 'remove':
						verify($this->securityKey, AccessMode::Remove);
						break;
				}
			}

			parent::init($options);
		}

		public function get() {
			$this->bind('id');

			$this->preGet();
			$this->doGet();
			$this->sendResponse();
		}

		public function page() {
			$this->bind('offset', 'limit', 'options');

			$this->offset = (int)$this->offset;
			$this->limit = $this->limit ? (int)$this->limit : $this->defaults['limit'];

			$this->prePage();
			$this->doPage();
			$this->sendResponse();
		}

		public function find() {
			$this->bind('options');

			$this->preFind();
			$this->doFind();
			$this->sendResponse();
		}

		public function insert() {
			$this->bind('item');

			$this->item = \JSON::parse($this->item);

			if ($this->item) {
				$this->preInsert();
				$this->doInsert();
				$this->sendResponse();
			}
		}

		public function update() {
			$this->bind('item');

			$this->item = \JSON::parse($this->item);

			if ($this->item) {
				$this->preUpdate();
				$this->doUpdate();
				$this->sendResponse();
			}
		}

		public function remove() {
			$this->bind('id');

			$this->preRemove();
			$this->doRemove();
			$this->sendResponse();
		}

		protected function preGet() {
		}

		protected function doGet() {
			$class = $this->record;

			$this->response = [
				'item' => $class::get($this->id, $this->getOptions)
			];
		}

		protected function prePage() {
			$this->pageOptions = [
				'count' => [],
				'items' => ['offset' => $this->offset, 'limit' => $this->limit]
			];
		}

		protected function doPage() {
			$class = $this->record;

			$count = (int)$class::count($this->pageOptions['count']);
			$items = $class::select($this->pageOptions['items']);

			$this->response = [
				'count' => $count,
				'items' => $items
			];
		}

		protected function preFind() {
			$this->findOptions = array_merge($this->findOptions, ['adjust']);;
		}

		protected function doFind() {
			$class = $this->record;

			$this->response = [
				'items' => $class::select($this->findOptions)
			];
		}

		protected function preInsert() {
		}

		protected function doInsert() {
			$class = $this->record;

			$this->response = [
				'item' => $class::insert($this->item, $this->insertOptions)
			];
		}

		protected function preUpdate() {
		}

		protected function doUpdate() {
			$class = $this->record;

			$this->response = [
				'item' => $class::update($this->item, $this->updateOptions)
			];
		}

		protected function preRemove() {
		}

		protected function doRemove() {
			$class = $this->record;

			$this->response = [
				'item' => $class::remove($this->id, $this->removeOptions)
			];
		}

		protected function sendResponse() {
			$this->xhrOk($this->response);
		}
	}
}
