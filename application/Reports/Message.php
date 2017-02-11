<?php

namespace Reports {
	use Reports\Data\Reports;

	class Message extends \Message {
		public static function reportCreate($report) {
			static::event('Reports.Report.Create', ['report' => $report], static::creatorCallback($report));
		}

		public static function reportInfo($report) {
			static::event('Reports.Report.Info', ['report' => $report], static::creatorCallback($report));
		}

		public static function reportProcessing($report) {
			static::event('Reports.Report.Processing', ['report' => $report], static::creatorCallback($report));
		}

		public static function reportReady($report) {
			static::event('Reports.Report.Ready', ['report' => $report], static::creatorCallback($report));
		}

		private static function creatorCallback($id) {
			$creator = static::creator($id);

			$callback = <<<JS
function validate(context, data) {
	if (context.user.id == $creator) {
		return true;
	}

	return false;
}
JS;
			return $callback;
		}


		private static function creator($report) {
			Reports::fromJSON($report, ['creator']);
			return ($report && isset($report['creator']['id']) && $report['creator']['id']) ? $report['creator']['id'] : 'null';
		}
	}
}
