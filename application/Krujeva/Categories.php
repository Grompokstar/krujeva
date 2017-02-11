<?php

namespace Krujeva {

	class Categories extends \Enum {

		const Cold = 1; // новые
		const Soup = 2; // новые
		const SecondDishes = 3; // новые
		//const Rolls = 4; // новые
		const Desert = 5; // новые
		const Drinks = 6; // новые
		const Dinner = 7; // новые
		const Salat = 8; // новые
		const Pasta = 9; // новые
		const HotSnaks = 10; // новые

		public static function title($value) {

			switch ($value) {

				case static::Cold:
					return 'Холодные закуски';
					break;

				case static::Soup:
					return 'Супы';
					break;

				case static::SecondDishes:
					return 'Вторые блюда';
					break;

				//case static::Rolls:
				//	return 'Роллы';
				//	break;

				case static::Desert:
					return 'Десерты';
					break;

				case static::Drinks:
					return 'Бар';
					break;

				case static::Dinner:
					return 'Деловой обед';
					break;

				case static::Salat:
					return 'Салаты';
					break;

				case static::Pasta:
					return 'Паста и Ризотто';
					break;

				case static::HotSnaks:
					return 'Горячие закуски';
					break;

			}
		}

		public static function formOptions($selected) {

			$options = [];

			foreach (static::items() as $name => $value) {

				$sel = '';

				if ($selected == $value) {
					$sel = ' selected="selected"';
				}

				$options[] = '<option value="' . $value . '"  '. $sel .'>' . static::title($value) . '</option>';
			}

			return implode('', $options);

		}
	}
}
