<?php

namespace Reports {
	use Utils\PHPExcel;

	abstract class WordReportWriter {
		protected $filePath;
		protected $data;


		public function __construct($filePath, $data) {
			$this->filePath = $filePath;
			$this->data = $data;

			$this->init();
		}

		protected abstract function write();

		/**
		 * Возвращает массив с названиями параметров и отформатированными значениями для вывода в отчете.
		 * @return array
		 */
		protected abstract function getFormattedParameters();

		public function run() {
			$this->write();

			$writer = \PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel2007');
			$writer->save($this->filePath);
		}

		/**
		 * Выводит заголовок отчета.
		 * Выводятся название, параметры генерации отчета, дата генерации.
		 * @param $name название отчета
		 */
		protected function writeHeader($name) {
			$this->writeName($name);
			$this->writeParameters();
			$this->writeCreatedTime();
		}

		protected function writeName($name) {
			$sheet = $this->phpExcel->getActiveSheet();

			$startColumn = 'A';
			$position = $startColumn . $this->rowIndex;

			$sheet->getStyle($position)->applyFromArray(Styles::$ReportNameStyle, false);
			$sheet->setCellValue($position, $name);

			$this->rowIndex += 2;
		}

		protected function writeParameters() {
			$parameters = $this->getFormattedParameters();

			if ($parameters) {
				$sheet = $this->phpExcel->getActiveSheet();

				$startColumn = 'A';

				$position = $startColumn . $this->rowIndex;
				$sheet->getStyle($position)->getFont()->setBold(true);
				$sheet->setCellValue($position, 'Параметры отчета:');
				$this->rowIndex++;

				foreach ($this->getFormattedParameters() as $param) {
					$value = $param['name'] . ': ' . $param['value'];

					$position = $startColumn . $this->rowIndex;
					$sheet->setCellValue($position, $value);

					$this->rowIndex++;
				}
			}

			$this->rowIndex++;
		}

		protected function writeCreatedTime() {
			$sheet = $this->phpExcel->getActiveSheet();

			$startColumn = 'A';
			$position = $startColumn . $this->rowIndex;

			$date = date('d.m.Y H:i:s', strtotime($this->data['datetime']));

			$sheet->getStyle($position)->getFont()->setBold(true);
			$sheet->setCellValue($position, 'Время создания: ' . $date);

			$this->rowIndex += 2;
		}

		protected function init() {
			$this->phpExcel = PHPExcel::getInstance();

			$this->phpExcel->getDefaultStyle()->applyFromArray(Styles::$PageStyle);
		}
	}
}
 