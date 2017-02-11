<?php


namespace Base {

	class Render {

		public static function render($layout = '', $filename = "", $args = [], $options = []) {

			$view = static::getHtml($filename, $args);

			$html = static::getHtml("Layouts/" . $layout, array_merge($args, [
				"content" => $view,
				//'title' => ''
			]));

			if (isset($options['return']) && $options['return']) {
				return $html;
			}

			echo $html;
		}

		public static function renderPartial($filename = '', $args = [], $options = []) {

			$html = static::getHtml($filename, $args);

			if (!$html) {
				return false;
			}

			if (isset($options['return']) && $options['return']) {
				return $html;
			}

			echo $html;
		}

		private static function getHtml($filename, $args = array()) {

			$fileurl = static::getFileUrl($filename);

			if (!$fileurl) {
				echo ('not found render file ' . $filename);
				exit();
			}

			ob_start();
			ob_implicit_flush(false);
			extract($args);
			require($fileurl);

			return ob_get_clean();
		}

		private static function getFileUrl($filename) {

			global $application;

			global $APP_PATH;

			$config = $application->configuration;

			$filepath = $APP_PATH.'/'.$config['viewsPath']. $filename.'.php';

			if (file_exists($filepath)) {
				return $filepath;
			}

			return false;
		}

	}

}