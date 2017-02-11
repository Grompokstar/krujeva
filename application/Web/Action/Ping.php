<?php

namespace Web\Action {
	use Security\Security;
	use Web\XHRAction;

	class Ping extends XHRAction {
		public function run() {
			$this->xhrOk(userid());
		}
	}
}
