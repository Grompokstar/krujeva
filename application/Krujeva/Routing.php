<?php

namespace Krujeva {

    class Routing {
		private static $prefix = '/application';
		private static $controllerNamespace = 'Krujeva/Web';

		private static $rules = [
			'/' => '/index/index',
			'/contacts' => '/index/contacts',
			'/menu' => '/index/menu',
			'/menu/view' => '/index/menuview',
			'/events' => '/index/events',
			'/events/view' => '/index/eventsview',
			'/news' => '/index/news',
			'/news/view' => '/index/newsview',
			'/admin/login' => '/admin/admin',
			//'/u<id:([0-9])+>' => '/users/get',
			//'/c<id:\d+>' => '/channel/view',
			//'/v<id:\d+>' => '/users/get',

			'<controller:\w+>' => '<controller>/index',
			'<controller:\w+>/<id:\d+>' => '<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
			'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			//'<controller:\w+>/<action:\w+>/*' => '<controller>/<action>', = если ставим /* - тогда все что идет после 2 слеша это аргументы теперь )) вот так вот круто прикинь
		];

		public static function controller($urlPath, $urlQuery, $options) {

			$className = static::parseUrl();

			if (!$className) {
				return false;
			}

			return static::createController($className);
		}

		private static function parseUrl() {

			$request = new \Base\CHttpRequest();

			$request->init();

			$rawPathInfo = $request->getPathInfo();

			$rules = [];

			foreach (static::$rules as $pattern => $route) {

				$rules[] = new \Base\CUrlRule($route, $pattern);
			}

			foreach ($rules as $rule) {

				if (($r = $rule->parseUrl($request, $rawPathInfo, $rawPathInfo)) !== false) {
					return $r;
				}
			}

			return false;
		}

		private static function createController($route) {

			$items = explode('/', $route);

			$actionName = array_pop($items);

			global $APP_PATH;

			$basePath = $APP_PATH . static::$prefix . '/' . static::$controllerNamespace;

			$route .= '/';

			if (($pos = strpos($route, '/')) !== false) {
				$id = substr($route, 0, $pos);

				if (!preg_match('/^\w+$/', $id))
					return null;

				$oldId = $id;

				$id = strtolower($id);

				$className = ucfirst($id);

				$className2 = $oldId;

				$classFile = $basePath . DIRECTORY_SEPARATOR . $className . '.php';

				$classFile2 = $basePath . DIRECTORY_SEPARATOR . $className2 . '.php';

				$className = str_replace('/', '\\', static::$controllerNamespace) . '\\' . $className;

				$className2 = str_replace('/', '\\', static::$controllerNamespace) . '\\' . $className2;

				if (!is_file($classFile)) {

					if (!is_file($classFile2)) {
						return false;
					}

					$classFile = $classFile2;
					$className = $className2;
				}

				if (!class_exists($className, true)) {
					require($classFile);
				}

				if (class_exists($className, false)) {
					return \Web\Controller::execute($className, $actionName);
				}

				return false;
			}
		}
    }
}
