<?php

namespace System\Web {
	use Web\Action;

	class Timestamp extends Action {
		public function run() {
			echo time();
		}
	}
}
