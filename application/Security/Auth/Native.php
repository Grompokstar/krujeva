<?php

namespace Security\Auth {
	use Security\AuthType;
	use Security\Context;
	use Security\Data\Users;

	class Native implements IAuth {
		/**
		 * @param Context $context
		 * @param array $options
		 * @return bool
		 */
		public static function auth($context, $options = []) {
			if (isset($options['login']) && isset($options['password'])) {
				if ($user = Users::findCredentials($options['login'], $options['password'], AuthType::Native)) {
					$context->setUser($user);

					return true;
				}
			}

			return false;
		}
	}
}
 