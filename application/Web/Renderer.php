<?php

namespace Web {

	class Renderer {
		protected $layout = "Layouts/Main";
		protected $title = "Action";
		protected $keywords = "";

		protected function render() {
			return call_user_func_array(array('Base\Render', 'render'), func_get_args());
		}

		public function renderPartial() {
			return call_user_func_array(array('Base\Render', 'renderPartial'), func_get_args());
		}

		private function getHtml($filename, $args = array()) {
			$fileurl = $this->getFileUrl($filename);

			if (!$fileurl) {
				return 'not found view file ' . $filename;
			}

			ob_start();

			ob_implicit_flush(false);

			extract($args);

			require($fileurl);

			return ob_get_clean();
		}

		private function getFileUrl($filename) {

			global $APP_PATH;

			$viewPath = sprintf('%s/application/Views/%s.php', $APP_PATH, str_replace('\\', '/', $filename));

			if (file_exists($viewPath)) {
				return $viewPath;
			}

			return false;
		}
	}
}