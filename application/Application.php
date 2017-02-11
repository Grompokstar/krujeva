<?php

class Application {
	/**
	 * @var array
	 */
	public $configuration;

	public $options;

	private $console = false;

	public function __construct($configuration, $options = []) {
		$this->configuration = $configuration;
		$this->options = $options;

		if (in_array('console', $options)) {
			$this->console = true;
		}
	}

	public function init() {
	}

	public function run() {
	}

	public function deinit() {
	}

	public function isConsole() {
		return $this->console;
	}
}

 