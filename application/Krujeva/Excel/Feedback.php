<?php

namespace Krujeva\Excel {

	class Feedback extends Excel {

		public function publicWrite() {
			$this->write();
		}

		protected function write() {
			$data = $this->data['data'];

			$sheet = $this->phpExcel->getActiveSheet();

			//@row 1
			$this->formColumnHeader('Дата', ['style' => $this->leftAlignStyle()]);
			$this->formColumnHeader('Имя', ['style' => $this->leftAlignStyle()]);
			$this->formColumnHeader('Телефон', ['style' => $this->leftAlignStyle()]);
			$this->formColumnHeader('Текст обращения', ['width' => 80, 'style' => $this->leftAlignStyle()]);

			$this->writeHeader('Обратная связь');

			$this->formColumnRow('datetime',  ['callback' => function ($item) { return date('d.m.Y H:i', strtotime($item['datetime'])) ;}]);
			$this->formColumnRow('name');
			$this->formColumnRow('phone');
			$this->formColumnRow('text');

			$this->drawRows($data);
		}

		public function setStyle() {
			return array(
				'borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM)),
				'alignment' => ['vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER]
			);
		}

		protected function leftAlignStyle() {
			return [
				'alignment' => [
					'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
					'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
				]
			];
		}

		protected function getFormattedParameters() {
			return [];
		}
	}
}
