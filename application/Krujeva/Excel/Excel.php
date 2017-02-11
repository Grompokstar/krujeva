<?php

namespace Krujeva\Excel {
	use Reports\ExcelReportWriter;
	use Reports\Styles;

	abstract class Excel extends ExcelReportWriter {
		protected $ABC = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

		protected $columnsHeader = [];

		protected $rowsHeader = [];

		protected $columns = [];

		protected $rowHeight = 60;

		protected $sheetsCount = 1;

		public function formColumnHeader($title, $options = []) {

			if (!isset($options['row'])) {
				$options['row'] = 1;
			}

			if (!isset($options['width'])) {
				$options['width'] = 30;
			}

			if (!isset($options['style'])) {
				$options['style'] = [];
			}

			if (!isset($options['rowspan'])) {
				$options['rowspan'] = 1;
			}

			if (!isset($options['colspan'])) {
				$options['colspan'] = 1;
			}

			$this->columnsHeader[] = ['title' => $title, 'options' => $options];
		}

		public function formColumnRow($column, $options = []) {

			if (!isset($options['style'])) {
				$options['style'] = [];
			}

			$this->columns[] = ['column' => $column, 'options' => $options];
		}

		public function drawHeader() {

			$this->calcColumnHeader();

			$sheet = $this->phpExcel->getActiveSheet();

			//@white borders
			$sheet->getDefaultStyle()->applyFromArray(array('borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'ffffff'), 'wrap' => true, 'alignment' => ['wrap' => true]))));

			$from = null;

			$to = null;

			$rowStyle = ['borders' => ['allborders' => ['color' => ['rgb' => 'e5e5e5'], 'style' => \PHPExcel_Style_Border::BORDER_THIN]], 'alignment' => array('horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER, 'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER, 'indent' => 1, 'wrap' => true), 'font' => ['color' => ['rgb' => '008cc2'], 'name' => 'SansSerif']];

			$level = 0;

			$summaryRow = 0;

			foreach ($this->rowsHeader as $row) {

				foreach ($row as $columnHeader) {

					$r = $columnHeader['options']['rowspan'] + $level;

					if ($summaryRow < $r) {

						$summaryRow = $r;
					}

					if (!$from) {
						$from = $columnHeader['_from'];
					}

					$to = $columnHeader['_to'];
				}

				$level++;
			}

			$this->rowIndex += $summaryRow;

			//style
			$sheet->getStyle($from . ':' . $to)->applyFromArray($rowStyle);

			foreach ($this->rowsHeader as $row) {

				$rowIndex = 0;

				foreach ($row as $columnHeader) {

					/* объединяем ячейки */
					if ($columnHeader['_from'] != $columnHeader['_to']) {
						$sheet->mergeCells($columnHeader['_from'] . ':' . $columnHeader['_to']);
					}

					$sheet->setCellValue($columnHeader['_from'], $columnHeader['title']);

					//ширина колонки
					$sheet->getColumnDimension($columnHeader['_column'])->setWidth($columnHeader['options']['width']);

					$rowIndex = $columnHeader['_row'];

					//style
					if (count($columnHeader['options']['style'])) {
						$sheet->getStyle($columnHeader['_from'] . ':' . $columnHeader['_to'])->applyFromArray($columnHeader['options']['style']);
					}

					$sheet->getStyle($columnHeader['_from'])->getAlignment()->setIndent(1);
				}

				//Высота строки
				$sheet->getRowDimension($rowIndex)->setRowHeight(40);

				/* ПЕРЕНОС ТЕКСТА */
				$sheet->getStyle($rowIndex)->getAlignment()->setWrapText(true);
			}
		}

		public function calcColumnHeader() {

			$rows = [];

			foreach ($this->columnsHeader as $columnHeader) {

				$row = $columnHeader['options']['row'];

				if (!isset($rows[$row])) {

					$rows[$row] = [];
				}

				$rows[$row][] = $columnHeader;
			}

			ksort($rows);

			$rowIndex = $this->rowIndex;

			$allBusy = [];

			foreach ($rows as &$row) {

				$index = 0;

				foreach ($row as &$columnHeader) {

					$c = $columnHeader['options']['colspan'];

					$r = $columnHeader['options']['rowspan'];

					//@verify $index
					$index = $this->getIndexByBusy($index, $rowIndex, $allBusy);

					$columnHeader = array_merge($columnHeader, ['_column' => $this->ABC($index), '_index' => $index, '_row' => $rowIndex]);

					//@BusyColumns
					$busy = [];

					$from = null;
					$to = null;

					for ($i = 0; $i < $c; $i++) {

						for ($j = 0; $j < $r; $j++) {

							$el = $this->ABC($columnHeader['_index'] + $i) . ($columnHeader['_row'] + $j);

							if (!$from) {
								$from = $el;
							}

							$to = $el;

							$busy[] = $el;
						}
					}

					$columnHeader = array_merge($columnHeader, ['_busy' => $busy, '_from' => $from, '_to' => $to]);

					$allBusy = array_merge($allBusy, $busy);

					$index += $c;
				}

				$rowIndex++;
			}

			$this->rowsHeader = $rows;
		}

		private function getIndexByBusy($index, $row, $busy = []) {

			$col = $this->ABC($index) . $row;

			if (in_array($col, $busy)) {

				return $this->getIndexByBusy(++$index, $row, $busy);
			}

			return $index;
		}

		public function ABC($index, $count = null) {

			$result = "";

			if (!isset($this->ABC[$index])) {
				$index = $index - count($this->ABC);
				$count++;
				return $this->ABC($index, $count);
			}

			if ($count != null) {
				$result = $this->ABC[($count - 1)] . $this->ABC[$index];
			} else {
				$result = $this->ABC[$index];
			}

			return $result;
		}

		public function drawRows($items, $options = []) {

			$this->calcColumnRows();

			$sheet = $this->phpExcel->getActiveSheet();

			$firstColumn = null;

			$lastColumn = null;

			foreach ($this->columns as $column) {

				if (!$firstColumn) {

					$firstColumn = $column;
				}

				$lastColumn = $column;
			}

			$lineindex = 1;

			$rowStyle = ['borders' => ['allborders' => ['color' => ['rgb' => 'e5e5e5'], 'style' => \PHPExcel_Style_Border::BORDER_THIN],], 'alignment' => array('horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT, 'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER, 'indent' => 1, 'wrap' => true), 'font' => ['color' => ['rgb' => '000000'], 'name' => 'SansSerif'],];

			foreach ($items as $item) {

				$row = $firstColumn['_column'] . $this->rowIndex . ':' . $lastColumn['_column'] . $this->rowIndex;

				$fillStyle = [];

				if ($this->rowIndex % 2 == 0) {
					$fillStyle = $this->fillRow($row);
				}

				if (isset($options['fillRow'])) {
					$fillStyle = $this->fillRow($row, $options['fillRow']);
				}

				//Высота строки
				$sheet->getRowDimension($this->rowIndex)->setRowHeight($this->rowHeight);

				$sheet->getStyle($row)->applyFromArray(array_merge($rowStyle, $fillStyle));

				foreach ($this->columns as $column) {
					if (isset($column['options']['callback'])) {
						$value = $column['options']['callback']($item, $lineindex, $this->data['data'], $column);
					} else {
						//$value = isset($item[$column['column']]) ? $item[$column['column']] : null;
						$keyPath = explode('.', $column['column']);
						$value = $item;
						foreach ($keyPath as $pathPart) {
							$value = isset($value[$pathPart]) ? $value[$pathPart] : null;
						}
					}

					if ($value === null && !isset($column['options']['empty'])) {
						$value = '-';
					}

					$sheet->setCellValue($column['_column'] . $this->rowIndex, $value);

					//style
					if (count($column['options']['style'])) {
						$sheet->getStyle($column['_column'] . $this->rowIndex)->applyFromArray($column['options']['style']);
					}
				}

				$this->rowIndex++;
				$lineindex++;
			}
		}

		protected function calcColumnRows() {

			$index = 0;

			foreach ($this->columns as &$column) {
				$column = array_merge($column, ['_column' => $this->ABC($index), '_index' => $index]);
				$index++;
			}
		}

		protected function fillRow($row, $color = 'F8F9FA') {

			return ['fill' => ['type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $color]]];
		}

		protected function writeHeader($name) {

			$this->calcColumnHeader();

			$this->writeName($name);

			$this->writeParameters();

			$this->writeCreatedTime();

			$this->drawHeader();
		}

		protected function writeName($name) {

			$sheet = $this->phpExcel->getActiveSheet();

			//@header Columns
			$from = null;
			$to = null;

			foreach ($this->rowsHeader as $row) {

				foreach ($row as $columnHeader) {

					if (!$from) {
						$from = $columnHeader['_column'] . $this->rowIndex;
					}

					$to = $columnHeader['_column'] . $this->rowIndex;
				}
			}

			$sheet->mergeCells($from . ':' . $to);

			$sheet->getStyle($from)->applyFromArray(Styles::$ReportNameCenterStyle, false);

			$sheet->setCellValue($from, $name);

			//Высота строки
			$sheet->getRowDimension($this->rowIndex)->setRowHeight(80);

			$sheet->getStyle($from)->getAlignment()->setIndent(1);

			$this->rowIndex += 2;
		}
	}
}