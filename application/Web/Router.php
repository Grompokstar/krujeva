<?php

namespace Web {
	class Router {
		public $urlPath;
		public $urlQuery;

		private $functions = [];

		public function __construct() {
			$url = parse_url($_SERVER['REQUEST_URI']);

			$this->urlPath = isset($url['path']) ? $url['path'] : '';
			$this->urlQuery = isset($url['query']) ? $url['query'] : '';
		}

		public function add($function, $options = []) {
			if (is_callable($function)) {
				$this->functions[] = ['function' => $function, 'options' => $options];
			}
		}

		public function run() {
			/**
			 * @var callable $function
			 */
			foreach ($this->functions as $item) {
				$function = $item['function'];

				if ($function($this->urlPath, $this->urlQuery, $item['options'])) {
					return;
				}
			}

			echo "Not found";
		}
	}
}

 