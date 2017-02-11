<?php

namespace Reports {

	class Styles {
		public static $Bold = [
			'font' => [
				'bold' => true,
			]
		];

		public static $PageStyle = [
			'font' => [
				'name' => 'Arial',
				'size' => '12',
			]
		];

		public static $ReportNameStyle = [
			'font' => [
				'bold' => true,
				'size' => '16',
			]
		];

		public static $ReportNameCenterStyle = [
			'alignment' => array('horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT, 'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER),
			'font' => ['color' => ['rgb' => '000000'], 'name' => 'SansSerif', 'size' => '32'],
		];

		public static $ColumnTitlesStyle = [
			'font' => [
				'bold' => true
			],
			'fill' => [
				'type' => \PHPExcel_Style_Fill::FILL_SOLID,
				'color' => ['rgb' => 'cdc9c9']
			],
			'borders' => [
				'allborders' => [
					'style' => \PHPExcel_Style_Border::BORDER_THIN
				]
			]
		];

		public static $ColumnSummaryStyle = [
			'font' => [
				'bold' => true,
				'italic' => true,
			],
			'borders' => [
				'allborders' => [
					'style' => \PHPExcel_Style_Border::BORDER_THIN
				]
			]
		];

		public static $ReportRowsStyle = [
			'borders' => [
				'allborders' => [
					'style' => \PHPExcel_Style_Border::BORDER_THIN,
					'color' => ['argb' => '999999'],
				]
			],
			'alignment' => [
				'wrap' => true,
				'shrinkToFit' => true
			]
		];
	}
}