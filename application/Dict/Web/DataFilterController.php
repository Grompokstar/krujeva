<?php

namespace Dict\Web {

	class DataFilterController extends \Web\DataController {
		protected $buildoptions = [];

		protected function prePage() {
			parent::prePage();

			if (isset($this->options['unlimit'])) {
				unset($this->pageOptions['items']['limit']);
				unset($this->pageOptions['items']['offset']);
			}

			$filter = $this->getFilter();

			$class = $this->record;

			$this->addOptions($this->pageOptions['items']);
			$this->addOptions($this->pageOptions['count']);

			if (method_exists($class, 'recordFilter')) {

				$class::recordFilter($this->pageOptions['items'], $filter);
				$class::recordFilter($this->pageOptions['count'], $filter);

			}
		}

		protected function preFind() {
			parent::preFind();

			if (isset($this->options['addOptions'])) {
				$this->addOptions($this->findOptions);
			}

			$this->findOptions = array_merge($this->findOptions, ['adjust']);

			$filter = $this->getFilter();

			$class = $this->record;

			if (method_exists($class, 'recordFilter')) {

				$class::recordFilter($this->findOptions, $filter);

			}
		}

		protected function doGet() {

			$class = $this->record;

			if (method_exists($class, 'recordFilter')) {
				$class::recordFilter($this->getOptions);
			}

			parent::doGet();

			if (method_exists($class, 'build')) {
				$class::build($this->response['item'], $this->buildoptions);
			}
		}

		protected function doInsert() {
			parent::doInsert();

			$class = $this->record;

			if (method_exists($class, 'build')) {
				$class::build($this->response['item'], $this->buildoptions);
			}
		}

		protected function doUpdate() {
			$class = $this->record;

			if (method_exists($class, 'recordFilter')) {
				$filterOptions = $this->updateOptions;

				$class::recordFilter($filterOptions);

				$item = $class::get($this->item['id'], $filterOptions);

				if (!$item) {
					return;
				}
			}

			parent::doUpdate();

			if (method_exists($class, 'build')) {
				$class::build($this->response['item'], $this->buildoptions);
			}
		}

		protected function doRemove() {
			$class = $this->record;

			if (method_exists($class, 'recordFilter')) {
				$filterOptions = $this->removeOptions;

				$class::recordFilter($filterOptions);

				$item = $class::get($this->id, $filterOptions);

				if (!$item) {
					return;
				}
			}

			parent::doRemove();
		}

		protected function getFilter() {

			$filter = isset($this->options['filter']) ? $this->options['filter'] : [];

			if (isset($this->options['autocomplete'])) {
				$filter['autocomplete'] = true;
			}

			return $filter;
		}

		protected function addOptions(&$options) {}

	}
}
