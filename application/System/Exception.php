<?php

namespace System {
	class Exception extends \Exception {
		public static $culture = Culture::RU;

		private $data = false;
		private $localization = [];

		/**
		 * @param string $message
		 * @param int $code
		 * @param mixed $data
		 */
		public function __construct($message = '', $code = 0, $data = false) {
			if (!$this->localization) {
				$this->initLocalization();
			}

			parent::__construct($this->localize($message), $code);

			$this->data = $data;
		}

		public function getData() {
			return $this->data;
		}

		private function localize($message) {
			if (isset($this->localization[static::$culture]) && isset($this->localization[static::$culture][$message])) {
				return $this->localization[static::$culture][$message];
			}

			return $message;
		}

		private function initLocalization() {
			$this->localization[Culture::RU] = [
				'Card is finished' => 'Карточка уже закрыта'
			];
		}
	}
}

 