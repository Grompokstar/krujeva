<?php

namespace Security {
	use Web\Controller;

	class Web extends Controller {
		public $__xhr = true;

		protected static $__actions = ['context', 'signin', 'signout'];

		protected $user;
		protected $login;
		protected $password;

		public function context() {
			$this->xhrOk(Security::$context->data);
		}

		public function signIn() {
			$this->bind('login', 'password');

			if (Security::signIn('Security\Auth\Native', ['login' => $this->login, 'password' => $this->password])) {
				$this->xhrOk(Security::$context->data);
			}
		}

		public function signOut() {
			Security::signOut();

			$this->xhrOk(Security::$context->data);
		}
    }
}
