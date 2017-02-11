<?php

namespace System {
	class Daemon {
		public static $active = false;

		public static function init($params = []) {
			pcntl_signal(SIGHUP, get_called_class() . '::signalHandler');
			pcntl_signal(SIGINT, get_called_class() . '::signalHandler');
			pcntl_signal(SIGTERM, get_called_class() . '::signalHandler');
		}

		public static function run($callback) {
			if (is_callable($callback)) {
				static::$active = true;

				while (static::$active) {
					$callback();
				}
			}
		}

		public static function log() {
			$datetime = datetime();

			echo "[$datetime]";

			foreach (func_get_args() as $arg) {
				echo " $arg";
			}

			echo "\n";
		}

		public static function signalHandler($signo) {
			switch ($signo) {
				case SIGHUP:
					static::onSignalHup();
					break;
				case SIGINT:
					static::onSignalInt();
					break;
				case SIGTERM:
					static::onSignalTerm();
					break;
			}
		}

		protected static function onSignalHup() {
			static::stop();
		}

		protected static function onSignalInt() {
			static::stop();
		}

		protected static function onSignalTerm() {
			static::stop();
		}

		protected static function stop() {
			static::$active = false;
		}
	}
}
