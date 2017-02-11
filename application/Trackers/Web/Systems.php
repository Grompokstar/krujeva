<?php

namespace Trackers\Web {
	use Web\DataController;

	class Systems extends DataController {
		protected $record = 'Trackers\Data\Systems';

		protected function prePage() {
			parent::prePage();

			$this->pageOptions['items'] = array_merge($this->pageOptions['items'], [
				'order' => 'name'
			]);
		}

		protected function preFind() {
			parent::preFind();

			$this->findOptions = array_merge($this->findOptions, [
				'order' => 'name',
				'adjust'
			]);

			if (isset($this->options['pattern'])) {
				$this->findOptions['where'] = 'lower(name) like $1';
				$this->findOptions['data'] = ['%' . mb_strtolower($this->options['pattern']) . '%'];

				if (isset($this->options['autocomplete']) && $this->options['autocomplete']) {
					$this->findOptions['limit'] = 20;
				}
			}
		}
	}
}
 