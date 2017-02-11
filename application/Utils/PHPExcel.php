<?php

namespace Utils {
	global $APP_PATH;
	include_once $APP_PATH . '/extensions/PHPExcel_1.8.0/PHPExcel.php';

	class PHPExcel {
		public static function getInstance() {
			return new \PHPExcel();
		}
	}
}
