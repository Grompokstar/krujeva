<?php

namespace Reports {
	use Reports\Data\Reports;
	use System\Code;
	use System\Exception;

	class Planner {
		public static $path = '';
		public static $excelPath = '';
		public static $wordPath = '';

		public static function init($options = []) {
			static::$path = option('path', $options);
			static::$excelPath = option('excelPath', $options);
			static::$wordPath = option('wordPath', $options);
		}

		public static function insert($className, $parameters, $format) {
			if (!class_exists($className)) {
				throw new Exception('Report class does not exist', Code::ReportFailed);
			}

			$parameters = \JSON::stringify($parameters);

			$report = Reports::insert([
				'classname' => $className,
				'parameters' => $parameters,
				'format' => $format,
				'status' => Status::Created
			]);

			$report['queueIndex'] = static::myQueueIndex($report['id']);

			Message::reportCreate($report);

			return $report;
		}

		public static function fetch() {
			txbegin();

			$report = Reports::first([
				'fields' => 'id',
				'where' => 'status = $1',
				'data' => [Status::Created],
				'order' => 'createddatetime',
				'forupdate'
			]);

			if ($report) {
				$report['status'] = Status::Processing;

				Reports::update($report);

				Message::reportProcessing($report);

				static::infoOthers();
			}

			txcommit();

			return $report['id'];
		}

		public static function ready($id) {
			$report = Reports::get($id);

			return isset($report['status']) ? $report['status'] == Status::Ready : false;
		}

		public static function finish($id) {
			$report = Reports::update([
				'id' => $id,
				'status' => Status::Ready
			]);

			Message::reportReady($report);

			return $report;
		}

		public static function data($id) {
			$filePath = static::$path . "$id.json";
			$data = null;

			if (@is_file($filePath)) {
				$data = \JSON::parse(file_get_contents($filePath));
			}

			return $data;
		}

		public static function infoOthers() {

			$reports = Reports::select([
				'where' => 'status = $1',
				'data' => [Status::Created]
			]);

			foreach ($reports as $report) {

				$report['queueIndex'] = static::myQueueIndex($report['id']);

				Message::reportInfo($report);
			}
		}

		public static function myQueueIndex($id) {

			$query = 'select count(1)
					from reports.reports
					where status = $1 and
						  createddatetime <= (select createddatetime from reports.reports where id = $2 limit 1)';

			return queryScalar($query, [Status::Created, $id]);
		}

		public static function exec($id) {
			if ($record = Reports::get($id)) {
				$className = $record['classname'];
				$format = $record['format'];
				$parameters = \JSON::parse($record['parameters']);
				$creator = \JSON::parse($record['creator']);

				/**
				 * @var Report $report
				 */
				$report = new $className($parameters, $creator);

				if (method_exists($report, 'initContext')) {
					$report->initContext();
				}

				$data = $report->run();

				$filePath = static::$path . "$id.json";

				$data = [
					'className' => $className,
					'format' => $format,
					'datetime' => datetime(),
					'creator' => $record['creator'],
					'parameters' => $parameters,
					'fileName' => '',
					'data' => $data
				];

				switch ($data['format']) {
					case Format::Excel:
						$data['fileName'] = static::createExcelReport($id, $data);
						break;
					case Format::Word:
						$data['fileName'] = static::createWordReport($id, $data);
						break;
				}

				file_put_contents($filePath, \JSON::stringify($data));

				static::finish($id);
			}
		}

		public static function getFilePath($format) {

			switch ($format) {
				case Format::Excel:
					return static::$excelPath;
					break;
				case Format::Word:
					return static::$wordPath;
					break;
			}

			throw new Exception('Bad format', Code::ReportFailed);
		}

		private static function createExcelReport($id, $data) {
			$className = explode('\\', $data['className']);
			array_splice($className, count($className) - 1, 0, ['Excel']);
			$className = implode('\\', $className);

			if (!class_exists($className)) {
				throw new Exception('Excel report writer class does not exist', Code::ReportFailed);
			}

			/**
			 * @var ExcelReportWriter $report
			 */
			$report = new $className(static::getFilePath(Format::Excel), $id, $data);

			$report->run();

			return $report->fileName();
		}

		private static function createWordReport($id, $data) {
			$className = explode('\\', $data['className']);
			array_splice($className, count($className) - 1, 0, ['Excel']);
			$className = implode('\\', $className);

			if (!class_exists($className)) {
				throw new Exception('Word report writer class does not exist', Code::ReportFailed);
			}

			/**
			 * @var WordReportWriter $report
			 */
			$report = new $className(static::getFilePath(Format::Word) . "$id.rtf", $data);
			$report->run();

			return "$id.rtf";
		}
	}
}
 