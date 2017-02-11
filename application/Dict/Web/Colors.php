<?php

namespace Dict\Web {
	use Web\DataController;

	class Colors extends DataController {
		protected $securityKey = 'Dict.Colors';

		protected $record = 'Dict\Data\Colors';

		protected function preFind() {
			parent::preFind();

			$this->findOptions = array_merge($this->findOptions, [
				'order' => 'name',
				'adjust'
			]);

			if (isset($this->options['autocomplete'])) {
				$this->findOptions['limit'] = 20;
			}

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(name) like $1';
				$this->findOptions['data'] = ['%' . (string)mb_strtolower($this->options['pattern']) . '%'];
			}
		}
	}
}
 