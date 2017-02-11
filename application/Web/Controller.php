<?php

namespace Web {
	class Controller extends Renderer {
		use Validators;

		protected static $__actions = [];

		protected $__action = null;

		protected $__options = [];

		public $__xhr = false;
		protected $__xhrResponse = ['success' => false, 'data' => null, 'message' => '', 'code' => 0];

		public function init($options = []) {
			$this->__options = array_merge($this->__options, $options);

			if (isset($options['action'])) {
				$this->__action = $options['action'];
			}
		}

		public function deinit() {
		}

		public function bind() {
			$params = func_get_args();

			if (is_array($params[0])) {
				$params = $params[0];
			}

			$variables = Request::data();

			foreach ($params as $param) {
				$this->$param = isset($variables[$param]) ? $variables[$param] : null;
			}
		}

		public function bindFiles() {
			$params = func_get_args();

			if (is_array($params[0])) {
				$params = $params[0];
			}

			foreach ($params as $param) {
				$this->$param = isset($_FILES[$param]) ? $_FILES[$param] : null;
			}
		}

		public static function isAction($method) {
			return in_array(strtolower($method), array_map('strtolower', static::$__actions));
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

		public static function execute($class, $action) {
			/**
			 * @var Controller $class
			 */
			if (is_a($class, 'Web\Controller', true)) {
				if (method_exists($class, $action) && $class::isAction($action)) {
					$options = [
						'action' => $action
					];

					\Security\Service::restoreContext();

					/**
					 * @var Controller $controller
					 */
					$controller = new $class();

					try {
						$controller->init($options);
						$controller->$action();

						if ($controller->__xhr) {
							$controller->sendXHRResponse();
						}

						$controller->deinit();
					} catch (\System\Exception $e) {
						if ($controller->__xhr) {
							$controller->xhrError($e->getMessage(), $e->getCode(), $e->getData());
							/**
							 * @var XHRAction $action
							 */
							$controller->sendXHRResponse();
						} else {
							echo $e->getMessage();
						}
					} catch (\Exception $e) {
						if ($controller->__xhr) {
							$controller->xhrError($e->getMessage(), $e->getCode());
							/**
							 * @var XHRAction $action
							 */
							$controller->sendXHRResponse();
						} else {
							echo $e->getMessage();
						}
					}

					return true;
				}
			}

			return false;
		}

		public function show($pageClass) {
			Page::show($this, $pageClass);
		}

		public function xhrOk($data = true, $message = '', $code = 0) {
			$this->__xhrResponse['success'] = true;
			$this->__xhrResponse['data'] = $data;
			$this->__xhrResponse['message'] = $message;
			$this->__xhrResponse['code'] = $code;
		}

		public function xhrError($message, $code = 0, $data = false) {
			$this->__xhrResponse['success'] = false;
			$this->__xhrResponse['message'] = $message;
			$this->__xhrResponse['code'] = $code;
			$this->__xhrResponse['data'] = $data;
		}

		public function sendXHRResponse() {
			header('Content-Type: application/json; charset=utf-8');
			echo \JSON::stringify(['success' => $this->__xhrResponse['success'], 'data' => $this->__xhrResponse['data'], 'message' => $this->__xhrResponse['message'], 'code' => $this->__xhrResponse['code']]);
		}

		public function index() {
			echo 'Not found';
		}
    }
}