<?php

namespace Security\Web {
	use Security\Data\Roles;
	use Web\DataController;
	use Data\Query;

	class Users extends DataController {
		protected $securityKey = 'Security.Users';

		protected $record = 'Security\Data\Users';

		protected function prePage() {
			parent::prePage();

			$this->pageOptions['items'] = array_merge($this->pageOptions['items'], [
				'fields' => 'users.*, roles.name as rolename',
				'join' => [
					[
						'table' => 'security.roles',
						'on' => 'users.roleid = roles.id'
					]
				],
				'order' => 'users.login'
			]);

			$query = new Query('security.users');

			if ($this->options && isset($this->options['filter'])) {
				$filter = array_merge(['id' => null, 'login' => null, 'name' => null, 'roleid' => null], $this->options['filter']);

				if ($filter['id'] && is_numeric($filter['id'])) {
					$query->where('users.id = $1', [$filter['id']]);
				}

				if ($filter['login']) {
					$query->where("lower(users.login) like '%' || $1 || '%'", [mb_strtolower($filter['login'])]);
				}

				if ($filter['name']) {
					$query->where("lower(users.name) like '%' || $1 || '%'", [mb_strtolower($filter['name'])]);
				}

				if ($filter['roleid']) {
					$query->where('users.roleid = $1', [$filter['roleid']]);
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
				'order' => 'users.login'
			]);

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(users.login) like $1 or lower(users.name) like $1';
				$this->findOptions['data'] = ['%' . mb_strtolower($this->options['pattern']) . '%'];

				if (isset($this->options['autocomplete']) && $this->options['autocomplete']) {
					$this->findOptions['limit'] = 20;
				}
			}
		}

		protected function doGet() {
			parent::doGet();

			\Security\Data\Users::build($this->response['item'], ['roles']);
		}

		protected function doInsert() {
			$this->prepareRoles($this->item);

			parent::doInsert();

			\Security\Data\Users::build($this->response['item'], ['roles']);
		}

		protected function doUpdate() {
			$this->prepareRoles($this->item);

			parent::doUpdate();

			\Security\Data\Users::build($this->response['item'], ['roles']);
		}

		private function prepareRoles(&$user) {
			if ($user && isset($user['roles'])) {
				$user['roles'] = array_column($user['roles'], 'id');
			}
		}
	}
}
 