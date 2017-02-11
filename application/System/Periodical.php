<?php

namespace System {
	abstract class Periodical {
		public abstract function run();

		protected function log() {
			call_user_func_array('System\Daemon::log', func_get_args());
		}
	}
}
 