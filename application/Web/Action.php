<?php

namespace Web {
	abstract class Action {
		use Validators;

		private $bindings = [];

		public function __construct() {
		}

		public function getParam($name) {
			return isset($this->$name) ? $this->$name : null;
		}

		public function setParam($name, $value) {
			if (isset($this->$name)) {
				$this->$name = $value;
			}
		}

		public function hasParam($name) {
			return isset($this->$name);
		}

		public function init() {
			$this->initBindings();
		}

		public function run() {
			echo 'Not found';
		}

		public static function execute($actionClass) {
			if (is_a($actionClass, 'Web\Action', true)) {
				/**
				 * @var Action $action
				 */
				$action = new $actionClass();

				try {
					$action->init();
					$action->run();

					if ($action instanceof XHRAction) {
						/**
						 * @var XHRAction $action
						 */
						$action->sendXHRResponse();
					}
				} catch (\System\Exception $e) {
					if ($action instanceof XHRAction) {
						$action->xhrError($e->getMessage(), $e->getCode(), $e->getData());
						/**
						 * @var XHRAction $action
						 */
						$action->sendXHRResponse();
					} else {
						echo $e->getMessage();
					}
				} catch (\Exception $e) {
					if ($action instanceof XHRAction) {
						$action->xhrError($e->getMessage(), $e->getCode());
						/**
						 * @var XHRAction $action
						 */
						$action->sendXHRResponse();
					} else {
						echo $e->getMessage();
					}
				}

				return true;
			}

			return false;
		}

		public function show($pageClass) {
			Page::show($this, $pageClass);
		}

		protected function bind($property, $method = RequestMethod::Unknown) {
			$this->bindings[$property] = $method;
		}

		protected function bindPost($property) {
			$this->bind($property, RequestMethod::Post);
		}

		protected function bindGet($property) {
			$this->bind($property, RequestMethod::Get);
		}

		protected function bindFile($property) {
			$this->bind($property, 'file');
		}

		private function initBindings() {
			$requestMethod = Request::method();
			$variables = Request::data();

			foreach ($this->bindings as $property => $method) {
				if ($method === 'file') {
					$this->$property = isset($_FILES[$property]) ? $_FILES[$property] : null;
				} else {
					if ($method != $requestMethod && $method != RequestMethod::Unknown) {
						$this->$property = null;
					} else {
						$this->$property = isset($variables[$property]) ? $variables[$property] : null;
					}
				}
			}
		}
	}
}
