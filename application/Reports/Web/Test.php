<?php
namespace Reports\Web {
	use Web\Action;

	class Test extends Action {
		protected $report;
		protected $parameters;

		public function __construct() {
			parent::__construct();

			$this->bind('report');
			$this->bind('parameters');
		}

		public function run() {
			$this->parameters = [
				'date' => '01.10.2014',
				'countedCalls' => 1000
			];

			$class = $this->report;
			$class = '\CallCenter\Report\DDSFunctioning';

			$report = new $class($this->parameters);
			$data = $report->run();

			print_r($data);
		}
	}
}
