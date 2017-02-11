<?php

namespace Security\Auth {
	use Security\AuthType;
	use Security\Context;
	use Security\Data\Users;

	class Request implements IAuth {
		/**
		 * @param Context $context
		 * @param array $options
		 * @return bool
		 */
		public static function auth($context, $options = []) {
			if (isset($_REQUEST['AUTH'])) {
				$auth = $_REQUEST['AUTH'];

				if (is_array($auth) && isset($auth['login']) && isset($auth['password'])) {
					if ($user = Users::findCredentials($auth['login'], $auth['password'], AuthType::Native)) {
						$context->setUser($user);

						return true;
					}
				}
			}

			return false;
		}
	}
}
 