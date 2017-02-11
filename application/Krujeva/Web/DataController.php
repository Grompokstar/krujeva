<?php

namespace Krujeva\Web {

	class DataController extends \Dict\Web\DataFilterController {
		protected $events;
		protected $removeEvent;

		protected function preGet() {
			parent::preGet();
			$this->bind('events');
			$this->bind('removeEvent');
		}

		protected function doGet() {
			parent::doGet();

			$class = $this->record;

			if (($this->response['item'] || $this->removeEvent) &&  $this->events && method_exists($class, 'countNewItems')) {
				$this->response['stats'] = $class::countNewItems();
			}
		}

	}
}
