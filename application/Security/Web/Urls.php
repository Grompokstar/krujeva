<?php

namespace Security\Web {
	use Security\AccessMode;
	use Web\DataController;
	use Data\Query;

	class Urls extends DataController {
		protected static $__actions = ['get', 'find', 'page', 'insert', 'update', 'remove', 'json'];

		protected $securityKey = 'Security.Urls';

		protected $record = 'Security\Data\Urls';

		public function json() {
			verify('Security.Urls', AccessMode::Read);

			$urls = \Security\Data\Urls::select([
				'fields' => 'urls.url, urls.access as mode, keys.name as key',
				'join' => [
					[
						'table' => 'security.keys',
						'on' => 'keys.id = urls.keyid'
					]
				]
			]);

			foreach ($urls as &$url) {
				settype($url['mode'], 'int');
				$url['modeName'] = AccessMode::value2Name($url['mode']);
			}

			$this->xhrOk($urls);
		}

		protected function prePage() {
			parent::prePage();

			$query = new Query('security.urls');

			$query->options($this->pageOptions['count']);
			$this->pageOptions['count'] = $query->options();

			$query->options($this->pageOptions['items']);
			$this->pageOptions['items'] = $query->options();
		}

		protected function doPage() {
			parent::doPage();

			\Security\Data\Urls::build($this->response['items'], ['array']);
		}

		protected function doGet() {
			parent::doGet();

			\Security\Data\Urls::build($this->response['item']);
		}

		protected function doUpdate() {
			parent::doUpdate();

			\Security\Data\Urls::build($this->response['item']);
		}

		protected function doInsert() {
			parent::doInsert();

			\Security\Data\Urls::build($this->response['item']);
		}
	}
}
