<?php

namespace Dict\Web {
	use Web\DataController;
	use Data\Query;

	class VehicleModels extends DataController {
		protected $securityKey = 'Dict.VehicleModels';

		protected $record = 'Dict\Data\VehicleModels';

		protected function prePage() {
			parent::prePage();

			$query = new Query('dict.vehiclemodels');

			if ($this->options && isset($this->options['filter'])) {
				$filter = array_merge(['name' => null], $this->options['filter']);

				if ($filter['name']) {
					$query->where("lower(name) like '%' || $1 || '%'", [mb_strtolower($filter['name'])]);
				}
			}

			$query->options($this->pageOptions['count']);
			$this->pageOptions['count'] = $query->options();

			$query->options($this->pageOptions['items']);
			$this->pageOptions['items'] = $query->options();
		}

		protected function preFind() {
			parent::preFind();

			$this->findOptions = array_merge($this->findOptions, [
				'order' => 'name',
				'adjust'
			]);

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(name) like $1';
				$this->findOptions['data'] = ['%' . mb_strtolower($this->options['pattern']) . '%'];
			}
		}
	}
}
 