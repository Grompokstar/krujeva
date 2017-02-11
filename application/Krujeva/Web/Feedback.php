<?php

namespace Krujeva\Web {

	class Feedback extends DataController {
		protected $record = 'Krujeva\Data\Feedback';
		protected static $__actions = ['insert', 'listItems'];


		public function listItems () {

			$data = \Krujeva\Data\Feedback::select([
				'order' => 'datetime desc'
			]);

			global $application;

			$config = $application->configuration;

			$report = new \Krujeva\Excel\Feedback($config['reports']['excelPath'], 1, ['data' => $data]);

			$report->publicWrite();

			header('Content-type: application/vnd.ms-excel');

			header('Content-Disposition: attachment; filename="Обратная связь.xlsx"');

			$writer = \PHPExcel_IOFactory::createWriter($report->objExcel(), 'Excel2007');

			$writer->save('php://output');

			exit();
		}
	}
}
 