<?php

namespace Reports {

	class TableWriter {
		private $phpExcel;
		private $sheet;
		private $columnTitlesStyle;
		private $rowsStyle;

		public function __construct($phpExcel) {
			$this->phpExcel = $phpExcel;
			$this->sheet = $this->phpExcel->getActiveSheet();

			$this->initStyles();
		}

		/**
		 * Простой вариант отчета в виде таблицы с заголовком.
		 * @param array $columns массив элементов вида [
		 *    'key' => ['name' => 'название колонки', 'width' => 'ширина столбца', 'rowsStyle' => 'стили для столбца с данными']
		 * ]
		 * @param array $rows
		 * @param int $rowIndex строка с которой начинается вывод таблицы
		 * @return int номер строки в которой завершился вывод таблицы
		 */
		public function write($columns, $rows, &$rowIndex, $summaryRow = null) {
			$sheet = $this->sheet;

			$columnsCount = count($columns);

			$startColumn = 'A';
			$endColumn = \PHPExcel_Cell::stringFromColumnIndex($columnsCount - 1);

			// заголовок таблицы
			$columnNames = [];
			foreach ($columns as $key => $column) {
				$columnNames[$key] = $column['name'];
			}

			$sheet->getStyle($startColumn . $rowIndex . ':' . $endColumn . $rowIndex)->applyFromArray(Styles::$ColumnTitlesStyle);
			$sheet->fromArray(array_values($columnNames), null, $startColumn . $rowIndex);
			$rowIndex++;

			// итого
			if ($summaryRow) {
				$sheet->getStyle($startColumn . $rowIndex . ':' . $endColumn . $rowIndex)->applyFromArray(Styles::$ColumnSummaryStyle);
				$sheet->fromArray(array_values($summaryRow), null, $startColumn . $rowIndex);
				$rowIndex++;
			}

			// строки
			$preparedRows = [];
			foreach ($rows as $row) {
				$tmp = [];
				foreach ($columns as $key => $column) {
					$tmp[] = isset($row[$key]) ? (string)$row[$key] : ''; // приведение к string, иначе не печатает нулевые значения
				}

				$preparedRows[] = $tmp;
			}

			$startRowsIndex = $rowIndex;
			$endRowsIndex = $rowIndex + count($preparedRows) - 1;

			$sheet->getStyle($startColumn . $startRowsIndex . ':' . $endColumn . $endRowsIndex)->applyFromArray(Styles::$ReportRowsStyle);
			$sheet->fromArray(array_values($preparedRows), null, $startColumn . $startRowsIndex);

			// применение стилей и ширины
			$i = 0;
			foreach ($columns as $key => $column) {
				$columnIndex = \PHPExcel_Cell::stringFromColumnIndex($i);

				if (isset($column['width'])) {
					$sheet->getColumnDimension($columnIndex)->setWidth($column['width']);
				}

				// задан стиль строк данных
				if (isset($column['rowsStyle'])) {
					$sheet->getStyle($columnIndex . $startRowsIndex . ':' . $columnIndex . $endRowsIndex)->applyFromArray($column['rowsStyle']);
				}

				$i++;
			}

			$rowIndex = $endRowsIndex + 1;

			// итого
			if ($summaryRow) {
				$sheet->getStyle($startColumn . $rowIndex . ':' . $endColumn . $rowIndex)->applyFromArray(Styles::$ColumnSummaryStyle);
				$sheet->fromArray(array_values($summaryRow), null, $startColumn . $rowIndex);
				$rowIndex++;
			}

			return $rowIndex;

			// авторасчет ширины колонок
//			for ($i = 0; $i < $columnsCount; $i++) {
//				$columnIndex = \PHPExcel_Cell::stringFromColumnIndex($i);
//				$sheet->getColumnDimension($columnIndex)->setAutoSize(true);
//			}
//			$sheet->calculateColumnWidths();
		}

		public function setColumnTitlesStyle($columnTitlesStyle) {
			$this->columnTitlesStyle = $columnTitlesStyle;
		}

		public function setRowsStyle($rowsStyle) {
			$this->rowsStyle = $rowsStyle;
		}

		public function getSheet() {
			return $this->sheet;
		}

		private function initStyles() {
			$this->setColumnTitlesStyle(Styles::$ColumnTitlesStyle);
			$this->setRowsStyle(Styles::$ReportRowsStyle);
		}
	}
}