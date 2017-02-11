<?php

namespace Reports {
	use Kladr\Kladr;
	use Kladr\Section;

	abstract class Report {
		protected $parameters;
		protected $creator;

		public function __construct($parameters, $creator = null) {
			$this->parameters = $parameters;
			$this->creator = $creator;
		}

		public abstract function run();

		protected function getDistrictConditionCode($code) {
			if (in_array($code, ['1600000100000', '1600000200000'])) {
				// Казань и Челны
				return Kladr::extractTo(Section::City, $code) . '%';
			}

			return Kladr::extractTo(Section::Region, $code) . '%';
		}
	}
}
 