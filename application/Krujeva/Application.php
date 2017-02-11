<?php

namespace Krujeva {

	class Application extends \Application {

		private $connection;

		public $security = null;

		private $router;

		public function __construct($configuration, $options = []) {
			parent::__construct($configuration, $options);
		}

		public function init() {
			parent::init();

			if ($this->isConsole()) {
				\Debug::$mode = \Debug::TextMode;
			}

			$connection = $this->configuration['connection']['default'];

			$this->connection = new \Data\Connection($connection);

			\Data\Record::$connection = $this->connection;

			\System\NumSeq::$connection = $this->connection;

			if (isset($this->configuration['redis'])) {

				\Data\Redis::init($this->configuration['redis']);
			}

			if (isset($this->configuration['message'])) {
				\Message::init($this->configuration['message']);
			}

			if (isset($this->configuration['cache']) && !$this->isConsole()) {
				\Data\Cache::init($this->configuration['cache']);
			}

			if (isset($this->configuration['elastic'])) {
				\Search\Elastic::init($this->configuration['elastic']);
			}

			$this->initRecordFilters();


			if (!$this->isConsole()) {

				$this->router = new \Web\Router();

				/**
				 * Add routing function.
				 */
				$this->router->add(['Krujeva\Routing', 'controller']);
				//$this->router->add(['Web\Routing', 'action']);
				//$this->router->add(['Web\Routing', 'controller'], ['initial' => 'City\Web', 'url' => '/']);
			}
		}

		public function initRecordFilters() {

		}

		public function run() {

			try {
				if (!$this->isConsole()) {
					$this->router->run();
				}
			} catch (\Exception $e) {}
		}

		public function deinit() {
			$this->connection->close();

			parent::deinit();
		}

	}
}

 
