<?php

namespace Reports {
	use Reports\Data\Reports;
	use Web\Controller;

	class Web extends Controller {
		const RecentHours = 24;
		public $__xhr = true;

		protected static $__actions = ['create', 'data', 'download', 'recent'];

		protected $id;
		protected $className;
		protected $parameters;
		protected $format;

		public function create() {
			$this->bind('className', 'parameters', 'format');

			$this->parameters = \JSON::parse($this->parameters);

			$this->parameters['creator'] = user();

			$report = Planner::insert($this->className, $this->parameters, $this->format);

			$this->xhrOk($report);
		}

		public function recent() {
			$reports = Reports::select([
				'where' => 'createddatetime >= current_timestamp - ($1 || \' hour\')::interval',
				'data' => [static::RecentHours],
				'order' => 'createddatetime asc',
				'limit' => 15,
				'adjust'
			]);

			$this->xhrOk($reports);
		}

		public function data() {
			$this->bind('id');

			$this->xhrOk(Planner::data($this->id));
		}

		public function download() {
			$this->bind('id');
			$this->bind('format');

			$data = Planner::data($this->id);

			if (!$data) {
				die('Файл не найден.');
			}

			$filePath = Planner::getFilePath($this->format). $data['fileName'];

			if (!$filePath) {
				die('Файл не найден.');
			}

			switch ($this->format) {
				case Format::Excel:
					$contentType = 'application/vnd.ms-excel';
					break;
				case Format::Word:
					$contentType = 'application/vnd.ms-word';
					break;
			}

			header('Content-type: ' . $contentType);
			header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');

			readfile($filePath);
		}
	}
}
