<?php

namespace Security\Web {
	use Web\DataController;
	use Data\Query;

	class Keys extends DataController {
		protected $securityKey = 'Security.Keys';

		protected $record = 'Security\Data\Keys';

		protected function prePage() {
			parent::prePage();

			$query = new Query('security.keys');

			if ($this->options && isset($this->options['filter'])) {
				$filter = array_merge(['name' => null, 'description' => null], $this->options['filter']);

				if ($filter['name']) {
					$query->where("lower(keys.name) like '%' || $1 || '%'", [mb_strtolower($filter['name'])]);
				}

				if ($filter['description']) {
					$query->where("lower(keys.description) like '%' || $1 || '%'", [mb_strtolower($filter['description'])]);
				}
			}

			$query->options($this->pageOptions['count']);
			$this->pageOptions['count'] = $query->options();

			$query->options($this->pageOptions['items'])->order('name');
			$this->pageOptions['items'] = $query->options();
		}

		protected function preFind() {
			parent::preFind();

			$this->findOptions = array_merge($this->findOptions, ['order' => 'name']);

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(name) like $1 or lower(description) like $1';
				$this->findOptions['data'] = ['%' . (string)mb_strtolower($this->options['pattern']) . '%'];
			}
		}
	}
}
 