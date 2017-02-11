<?php

namespace Reports {
	use Utils\PHPExcel;

	abstract class ExcelReportWriter {
		public $filePath;
		protected $reportId;
		protected $data;

		protected $phpExcel;
		protected $rowIndex = 1;

		public function __construct($filePath, $id, $data) {

			$this->filePath = $filePath;
			$this->reportId = $id;
			$this->data = $data;

			$this->init();
		}

		public function objExcel() {

			return $this->phpExcel;
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
			$writer->save($this->filePath . $this->fileName());
		}

		protected function excelName() {

			return $this->reportId;
		}

		public function fileName() {

			return $this->excelName() . '.xlsx';
		}

		/**
		 * Выводит заголовок отчета.
		 * Выводятся название, параметры генерации отчета, дата генерации.
		 * @param string $name название отчета
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

			//$date = date('d.m.Y H:i:s', strtotime($this->data['datetime']));

			$sheet->getStyle($position)->getFont()->setBold(true);
			//$sheet->setCellValue($position, 'Время создания: ' . $date);

			$this->rowIndex += 2;
		}

		protected function init() {

			$this->phpExcel = PHPExcel::getInstance();

			$this->phpExcel->getDefaultStyle()->applyFromArray(Styles::$PageStyle);
		}

		public static function load($file_url) {

			$phpExcel = PHPExcel::getInstance();

			try {
				$objPHPExcel = \PHPExcel_IOFactory::load($file_url);
			}
			catch (Exception $e) {
				echo $e->getMessage();
				exit();
			}

			return $objPHPExcel;
		}

		/*STYLE*/
		/*
		 *  $args = $key -> $value
		 *
		 * $key 			$value
		 * = background      000
		 * = color 			000
		 * = font-size		27
		 * = font-family	Arial
		 * = font-weight	true
		 * = border			000
		 * */
		public function getStyle($args = array()) {

			$style = array();

			//background color
			if (isset($args["background"])) {
				$style["fill"] = $this->fillStyle($args["background"]);
			}

			//font
			if (isset($args["color"]) || isset($args["font-size"]) || isset($args["font-family"]) || isset($args["font-weight"])
			) {
				$style["font"] = $this->fontStyle($args);
			}

			//borders
			if (isset($args["border"])) {
				$style["borders"] = $this->borderStyle($args["border"]);
			}

			//alignment
			if (isset($args["align"])) {
				$style["alignment"] = $this->alignStyle($args["align"]);
			}

			return $style;
		}

		private function fillStyle($color = "FFDD00") {

			return array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => self::coalese($color, "FFDD00")));
		}

		private function fontStyle($args) {

			$style = array();

			if (isset($args["color"])) {
				$style["color"] = array("rgb" => self::coalese($args["color"], "000000"));
			}

			if (isset($args["font-size"])) {
				$style["size"] = self::coalese($args["font-size"], 14);
			}

			if (isset($args["font-family"])) {
				$style["name"] = self::coalese($args["font-family"], "Arial");
			}

			if (isset($args["font-weight"])) {
				$style["bold"] = self::coalese((bool)$args["font-weight"], false);
			}

			return $style;
		}

		private function borderStyle($borderColor) {

			return array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_HAIR, 'color' => array('rgb' => self::coalese($borderColor, "000000"))));
		}

		private function alignStyle($value) {

			$align = \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER;

			switch ($value) {
				case "left":
					$align = \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT;
					break;
				case "right":
					$align = \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT;
					break;
				case "center":
					$align = \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER;
					break;
				case "justify":
					$align = \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_JUSTIFY;
					break;
			}

			return array('horizontal' => $align, 'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER);
		}

		private static function coalese($v1, $v2) {

			if ($v1) {
				return $v1;
			}

			return $v2;
		}
	}
}
 