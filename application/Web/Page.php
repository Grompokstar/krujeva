<?php

namespace Web {
	class Page {
		/**
		 * @var Action|Controller $parent
		 */
		private $parent;
		private $nestedPage;
		private $currentViewClass = null;

		public static function show($parent, $pageClass) {
			echo self::content($parent, $pageClass);
		}

		public static function content($parent, $pageClass) {
			/**
			 * @var Page $page
			 */
			$page = new $pageClass($parent);
			$page->init();
			$page->run();
			$ret = $page->html();

			return $ret;
		}

		public final function parentView() {
			global $APP_PATH;

			$classes = class_parents(get_class($this), true);

			foreach ($classes as $class) {
				$viewPath = sprintf('%s/Views/%s.php', $APP_PATH, str_replace('\\', '/', $class));

				if (is_file($viewPath) && is_readable($viewPath)) {
					ob_start();

					include($viewPath);

					$content = ob_get_contents();
					ob_end_clean();

					return $content;
				}
			}

			return '';
		}

		public function __construct($parent) {
			$this->parent = $parent;
			$this->nestedPage = null;
		}

		public function __toString() {
			return $this->html(true);
		}

		public function parent() {
			return $this->parent;
		}

		public function has($property) {
			return $this->parent->hasParam($property);
		}

		public function get($property) {
			if ($this->parent->hasParam($property)) {
				return $this->parent->getParam($property);
			}

			return null;
		}

		public function set($property, $value) {
			if ($this->parent->hasParam($property)) {
				$this->parent->setParam($property, $value);
			}
		}

		public function nestedPage($page = null) {
			if ($page === null) {
				return $this->nestedPage;
			} else {
				$this->nestedPage = $page;
				return true;
			}
		}

		public function init() {
		}

		public function run() {
		}

		public final function html($__sysCall = false) {
			global $APP_PATH;

			if (($pageClass = $this->masterPage()) && !$__sysCall) {
				/**
				 * @var Page $page
				 */
				$page = new $pageClass($this->parent);
				$page->nestedPage($this);
				$page->init();
				$page->run();
				$ret = $page->html();

				return $ret;
			} else {
				$className = get_class($this);

				$classes = class_parents($className, true);
				array_unshift($classes, $className);

				foreach ($classes as $class) {
					$this->currentViewClass = $class;

					$viewPath = sprintf('%s/application/Views/%s.php', $APP_PATH, str_replace('\\', '/', $class));

					if (is_file($viewPath) && is_readable($viewPath)) {
						ob_start();

						include($viewPath);

						$content = ob_get_contents();
						ob_end_clean();

						return $content;
					}
				}
			}

			return null;
		}

		public function masterPage() {
			return null;
		}
	}
}
