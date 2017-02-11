<?php

namespace Web {
    class Routing {
		public static function action($urlPath, $urlQuery, $options) {
			$default = 'Index';

			$path = preg_replace('/\/+/', '\\', $urlPath);

			$actionClass = trim($path, ' \\');

			if ($actionClass == '\\') {
				if (isset($options['initial'])) {
					$actionClass = $options['initial'];
				}
			} else {
				if (!class_exists($actionClass, true)) {
					$actionClass = $actionClass . '\\' . $default;
				}
			}

			return Action::execute($actionClass);
		}

		public static function controller($urlPath, $urlQuery, $options) {
			$path = preg_replace('/\/{2,}/', '/', $urlPath);
			$path = trim($path, ' /');

			$items = $path ? explode('/', $path) : [];

			$className = null;
			$actionName = null;

			if ($items) {
				$className = implode('\\', $items);

			} else if (isset($options['initial'])) {
				$className = $options['initial'];
			}

			$actionName = isset($options['default']) ? $options['default'] : 'index';

			if (!class_exists($className, true)) {
				$actionName = array_pop($items);
				$className = implode('\\', $items);
			}

			return Controller::execute($className, $actionName);
		}

		public static function callback($urlPath, $urlQuery, $options) {
			if (isset($options['callback']) && is_callable($options['callback'])) {
				$callback = $options['callback'];

				return $callback($urlPath, $urlQuery, $options);
			}

			return false;
		}
    }
}
