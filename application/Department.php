<?php

namespace {
	class Department extends \Enum {
		const Other = 0;
		const Health = 1;
		const Police = 2;
//		const Patrol = 3;
		const Emergency = 4;
		const Community = 5;
		const Transport = 6;
		const Education = 7;
		const Ministry = 8;

		public static function title($value) {
			switch ($value) {
				case static::Other:
					return "Другое";
				case static::Health:
					return "МЗ";
				case static::Police:
					return "МВД";
				case static::Emergency:
					return "МЧС";
				case static::Community:
					return "ЖКХ";
				case static::Transport:
					return "Минтранс";
				case static::Education:
					return "Минобр";
				case static::Ministry:
					return "Кабмин";
			}

			return parent::title($value);
		}
	}
}
