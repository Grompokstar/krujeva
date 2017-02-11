<?php

namespace Security {
	use Security\Data\Roles;
	use Security\Data\Users;
	use Web\Session;

	class Context {
		const SessionKey = '__SecurityContext';

		public $data = [];

		public $timeout = 2592000;

		public function __construct() {
			$this->init();
		}

		public function isValid() {
			return (bool)@$this->data['valid'];
		}

		public function init() {
			$this->data['valid'] = false;
			$this->data['user'] = null;
			$this->data['role'] = null;
			$this->data['sessionId'] = null;
		}

		public function getTimeout() {

			return $this->timeout;
		}

		public function setUser($user, $sessionId = null) {
			$this->data['user'] = $user;
			$this->data['sessionId'] = $sessionId;

			$role = null;

			$roles = null;

			if ($user) {

				$this->data['valid'] = true;

				$role = Users::getRole($user);

				$roles = Users::getRoles($user, ['access']);

			} else {

				$this->data['valid'] = false;

			}

			$this->setRole($role, $roles);
		}

		private function setRole($role, $roles) {
			if ($role) {
				Roles::readAccess($role);

				$access = [];

				foreach ($role['access'] as $item) {
					$access[$item['name']] = pgIntArrayDecode($item['mode']);
				}

				$role['access'] = $access;

				if ($roles) {
					foreach ($roles as $item) {
						foreach ($item['access'] as $access) {
							$mode = pgIntArrayDecode($access['mode']);
							$name = $access['name'];

							if (!isset($role['access'][$name])) {
								$role['access'][$name] = $mode;
							} else {
								$role['access'][$name] = array_unique(array_merge($role['access'][$name], $mode));
							}
						}
					}
				}
			}

			$this->data['role'] = $role;
		}

		public function save() {

			$timeout = $this->getTimeout();

			ini_set('session.gc_maxlifetime', $timeout);

			$contextId = context('sessionId');

			$key = $contextId . static::SessionKey;

			\Data\Cache::set($key, \JSON::stringify($this->data), [
				'timeout' => $timeout
			]);
		}

		public function remove() {

			$contextId = context('sessionId');

			$key = $contextId . static::SessionKey;

			\Data\Cache::set($key, 'false', ['timeout' => 10]);

			$this->init();
		}

		public function restore($contextId) {

			$key = $contextId . static::SessionKey;

			$data = \Data\Cache::get($key);

			if ($data) {

				$data = \JSON::parse($data);

				if ($data) {

					foreach ($data as $key => $value) {
						$this->data[$key] = $value;
					}
				}

			}
		}
	}
}

 