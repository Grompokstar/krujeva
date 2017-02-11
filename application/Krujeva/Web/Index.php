<?php

namespace Krujeva\Web {

	class Index extends \Web\Controller {
		protected static $__actions = ['index', 'contacts', 'menu', 'events', 'news', 'eventsview', 'login', 'logout', 'timestamp', 'menuview'];

		public function timestamp() {
			echo time();
		}

		public function index() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/IndexMobile');

			} else {

				$this->render('Main', 'Index');
			}
		}

		public function contacts() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/ContactsMobile');

			} else {

				$this->render('Main', 'Contacts');
			}
		}

		public function menu() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/MenuMobile');
			} else {

				$this->render('Main', 'Menu');
			}
		}

		public function menuview() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/MenuViewMobile');

			} else {

				$this->render('Main', 'MenuView');
			}
		}

		public function events() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/EventsMobile');
			} else {

				$this->render('Main', 'Events');
			}
		}

		public function news() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/NewsMobile');

			} else {

				$this->render('Main', 'News');
			}
		}

		public function newsview() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/NewsViewMobile');
			} else {

				$this->render('Main', 'NewsView');
			}
		}

		public function eventsview() {

			if (\Utils::isMobile()) {

				$this->render('MainMobile', 'Mobile/EventsViewMobile');
			} else {

				$this->render('Main', 'EventsView');
			}
		}



		public function login() {

			$this->__xhr = true;

			$this->bind('login', 'password');

			if (!$this->login || !$this->password) {

				$this->xhrError('undefined login');

				return;
			}

			$context = \Security\Service::login($this->login, $this->password);

			if (!$context) {

				$this->xhrError('error login');

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
 