<?php

namespace Security {
	use Web\Action;
	use Web\Controller;

	class Routing {
		public static function auth($urlPath, $urlQuery, $options) {
			if (Security::signedIn()) {
				return false;
			}

			$public = coalesce($options['public'], []);
			$auth = coalesce($options['auth'], []);
			$controller = @$options['controller'];
			$action = @$options['action'];

			if (!is_array($public)) {
				$public = [$public];
			}

			if (in_array($urlPath, $public)) {
				return false;
			}

			if (Security::signIn($auth)) {
				return false;
			}

			if ($controller) {
				Controller::execute($controller, $action);
			} else {
				Action::execute($action);
			}

			return true;
		}
	}
}
 