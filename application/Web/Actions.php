<?php

namespace Web {
    class Actions extends Controller {
		protected static $__actions = ['ping'];

		public function ping() {
			$this->__xhr = true;

			$this->xhrOk(userid());
		}
    }
}
