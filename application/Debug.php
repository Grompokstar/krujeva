<?php

namespace {
	class Debug {
		const HtmlMode = 0;
		const TextMode = 1;

		public static $mode = Debug::HtmlMode;
		public static $filePath = '/var/log/glonass/debug.log';
		public static $tabs = 2;

		public static function dump() {
			$args = func_get_args();

			foreach ($args as $var) {
				echo self::output($var);
			}
		}

		public static function trace() {
			ob_start();
			debug_print_backtrace();
			$info = ob_get_contents();
			ob_end_clean();

			echo self::output($info);
		}

		public static function log($message) {
			$content = date('Y-m-d H:i:s') .' ' . $message . "\n";

			if ($f = fopen(self::$filePath, 'a')) {
				fputs($f, $content);
				fclose($f);
			}
		}

		public static function logDump() {
			$args = func_get_args();
			$message = '';

			foreach ($args as $var) {
				$message .= self::output($var, self::TextMode, self::$tabs);
			}

			self::log($message);
		}

		public static function logTrace() {
			ob_start();
			debug_print_backtrace();
			$message = self::output(ob_get_contents(), self::TextMode, self::$tabs);
			ob_end_clean();

			self::log($message);
		}

		private static function output($data, $mode = null, $tabs = 0) {
			if ($mode === null) {
				$mode = self::$mode;
			}

			switch ($mode) {
				case self::HtmlMode:
					ob_start();
					var_dump($data);
					$content = ob_get_contents();
					ob_end_clean();
					return str_replace("\n", '<br>', str_replace(' ', '&nbsp;&nbsp;', $content)) . '<br>';
					break;
				case self::TextMode:
					$ret = print_r($data, true);

					if ($tabs > 0) {
						$tabs = str_repeat("\t", $tabs);
						$ret = str_replace("\n", "\n$tabs", $ret);
					}
					return $ret . "\n";
					break;
			}

			return "Unknown debug mode<br>\n";
		}
	}
}
