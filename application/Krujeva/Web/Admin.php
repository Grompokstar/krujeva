<?php

namespace Krujeva\Web {

	class Admin extends \Web\Controller {
		protected static $__actions = ['index', 'pagelogin', 'login', 'logout', 'news', 'newsadd', 'events', 'eventsadd', 'products', 'productsadd', 'slider', 'slideradd', 'sliderevents', 'slidereventsadd', 'feedback'];

		protected function isLogIn($redirect = false) {

			$context = context('user');

			if ($redirect && !$context) {
				header("Location: /admin/pagelogin");
				exit();
			}

			return $context;
		}

		public function index() {
			$this->isLogIn(true);

			$this->render('Admin', 'Admin/IndexAdmin');
		}

		public function pagelogin() {
			$this->render('AdminLogin', 'Admin/Login');
		}

		public function news() {
			$this->render('Admin', 'AdminNews/List');
		}

		public function newsadd() {
			$this->render('Admin', 'AdminNews/Add');
		}

		public function events() {

			$this->render('Admin', 'AdminEvents/List');
		}

		public function eventsadd() {

			$this->render('Admin', 'AdminEvents/Add');
		}

		public function products() {

			$this->render('Admin', 'AdminProducts/List');
		}

		public function productsadd() {

			$this->render('Admin', 'AdminProducts/Add');
		}

		public function slider() {

			$this->render('Admin', 'AdminSlider/List');
		}

		public function slideradd() {

			$this->render('Admin', 'AdminSlider/Add');
		}

		public function sliderevents() {

			$this->render('Admin', 'AdminSliderEvents/List');
		}

		public function slidereventsadd() {

			$this->render('Admin', 'AdminSliderEvents/Add');
		}

		public function feedback() {

			$this->render('Admin', 'AdminFeedback/List');
		}



		public function login() {

			$this->__xhr = true;

			$this->bind('login', 'password');

			if (!$this->login || !$this->password) {

				$this->xhrError('Не указан логин');

				return;
			}

			$context = \Security\Service::login($this->login, $this->password);

			if (!$context) {

				$this->xhrError('Неправильный логин или пароль');

				return;
			}

			unset($context['sessionId']);

			$this->xhrOk($context);
		}

		public function logout() {

			$this->__xhr = true;

			\Security\Service::logout();

			$this->xhrOk();
		}

	}
}
 