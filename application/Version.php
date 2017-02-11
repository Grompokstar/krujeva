<?php

namespace {
	use Web\Action;

	class Version extends Action {
		public function run() {
			global $APP_PATH;

			$filePath = $APP_PATH . '/version';

			$version = '';

			if (is_file($filePath) && is_readable($filePath)) {
				$version = file_get_contents($filePath);
			}

			echo $version;
		}
	}
}
 