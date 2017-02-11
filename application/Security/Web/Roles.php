<?php

namespace Security\Web {
	use Web\DataController;
	use Data\Query;

	class Roles extends DataController {
		protected $securityKey = 'Security.Roles';

		protected $record = 'Security\Data\Roles';

		protected function sendResponse() {
			if ($this->response && in_array($this->__action, ['get', 'insert', 'update'])) {
				if ($this->response['item']) {
					\Security\Data\Roles::readAccess($this->response['item']);
				}
			}

			parent::sendResponse();
		}

		protected function prePage() {
			parent::prePage();

			$this->pageOptions['items'] = array_merge($this->pageOptions['items'], [
				'order' => 'name'
			]);

			$query = new Query('security.roles');

			if ($this->options && isset($this->options['filter'])) {
				$filter = array_merge(['name' => null, 'description' => null], $this->options['filter']);

				if ($filter['name']) {
					$query->where("lower(roles.name) like '%' || $1 || '%'", [mb_strtolower($filter['name'])]);
				}

				if ($filter['description']) {
					$query->where("lower(roles.description) like '%' || $1 || '%'", [mb_strtolower($filter['description'])]);
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
				$this->findOptions['data'] = ['%' . (string)mb_strtolower($this->options['pattern']) . '%'];
			}
		}
	}
}
 