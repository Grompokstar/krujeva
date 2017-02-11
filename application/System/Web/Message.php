<?php

namespace System\Web {
	use Web\Action;

	class Message extends Action {
		protected $name;
		protected $data;

		public function __construct() {
			$this->bind('name');
			$this->bind('data');
		}

		public function run() {
			$this->validateParamDecodeJSON($this->data);

			\Message::event($this->name, $this->data);
		}
	}
}
