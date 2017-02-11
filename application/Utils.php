<?php

	class Utils {

		public static function eq($title) {
			return htmlspecialchars($title);
			//return str_replace('"', '/"', $title);
		}

		public static function  seconds2format($seconds){
			if ($seconds < 0) {
				return "00:00:00";
			}

			$hours = 0;
			$minutes = 0;

			$hours = (int)($seconds / (60 * 60));
			if ($hours) {
				$seconds = $seconds - ($hours * (60 * 60));
			}

			$minutes = (int)($seconds / 60);
			if ($minutes) {
				$seconds = $seconds - ($minutes * 60);
			}

			if($hours <= 9){
				$hours = "0".$hours;
			}

			if ($minutes <= 9) {
				$minutes = "0" . $minutes;
			}

			if ($seconds <= 9) {
				$seconds = "0" . $seconds;
			}

			return $hours.":".$minutes.":".$seconds;
		}

		public static function seconds2text($seconds) {
			if ($seconds < 0) {
				return "0 секунд";
			}

			$days = 0;
			$hours = 0;
			$minutes = 0;

			$days = (int)($seconds / (60 * 60 * 24));
			if ($days) {
				$seconds = $seconds - ($days * (60 * 60 * 24));
			}

			$hours = (int)($seconds / (60 * 60));
			if ($hours) {
				$seconds = $seconds - ($hours * (60 * 60));
			}

			$minutes = (int)($seconds / 60);
			if ($minutes) {
				$seconds = $seconds - ($minutes * 60);
			}

			$result = "";

			if ($days) {
				$result .= $days . " " . self::makeTitle($days, array("день ", "дня ", "дней "));
			}

			if ($hours) {
				$result .= $hours . " " . self::makeTitle($hours, array("час ", "часа ", "часов "));
			} else if ($days) {
				$result .= "0 часов ";
			}

			if ($minutes) {
				$result .= $minutes . " " . self::makeTitle($minutes, array("минута ", "минуты ", "минут "));
			} else if ($hours) {
				$result .= "0 минут ";
			}

			if ($seconds > 0) {
				$result .= $seconds . " " . self::makeTitle($seconds, array("секунда ", "секунды ", "секунд "));
			} else {
				$result .= "0 секунд ";
			}

			return $result;
		}

		public static function explodeTimeInterval($startDate = '', $endDate = '', $step = 3600, $style = 'H:i') {

			$dates = [];

			if (!$startDate || !$endDate) {
				return $dates;
			}

			$startDate = \Date::parse($startDate);
			$endDate = \Date::parse($endDate);

			if ($endDate < $startDate) {
				return $dates;
			}

			for ($d = $startDate; $d <= $endDate; $d = $d + $step) {
				$dates[] = date($style, $d);
			}

			return $dates;
		}

		public static function explodeDateInterval($startDate = '', $endDate = '') {
			$dates = [];

			if (!$startDate || !$endDate) {
				return $dates;
			}

			$startDate = \Date::parse($startDate);
			$endDate = \Date::parse($endDate);

			if ($endDate < $startDate) {
				return $dates;
			}

			$dayStep = 60 * 60 * 24; // 1 day
			for ($d = $startDate; $d <= $endDate; $d = $d + $dayStep) {
				$dates[] = date('Y-m-d', $d);
			}

			return $dates;
		}

		/**
		 * Функция склонения числительных в русском языке
		 *
		 * @param int    $number Число которое нужно просклонять
		 * @param array  $titles Массив слов для склонения
		 * @return string
		 **/
		public static function makeTitle($number, $titles) {

			$number = (int)$number;

			if ($number < 0) {
				$number = 0;
			}

			$cases = array(2, 0, 1, 1, 1, 2);

			$key = ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)];
			return (isset($titles[$key])) ? $titles[$key] : $titles[0];
		}

		public static function array_sort($array, $on, $order = SORT_ASC) {
			$new_array = array();
			$sortable_array = array();

			if (count($array) > 0) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							if ($k2 == $on) {
								$sortable_array[$k] = $v2;
							}
						}
					} else {
						$sortable_array[$k] = $v;
					}
				}

				switch ($order) {
					case SORT_ASC:
						asort($sortable_array);
						break;
					case SORT_DESC:
						arsort($sortable_array);
						break;
				}

				foreach ($sortable_array as $k => $v) {
					$new_array[] = $array[$k];
				}
			}

			return $new_array;
		}


		public static function showQuery($sql, $params = []) {
			$result = $sql;

			foreach ($params as $k => $v) {
				$result = str_replace('$' . ($k + 1), "'" . $v . "'", $result);
			}

			return $result;
		}

		public static function localeDatetime($timestamp, $timezone = 0, $format = 'Y-m-d H:i:s') {
			return date($format, $timestamp + ($timezone * 3600) );
		}

		public static function localDate($date, $needYear = false) {

			$day = date('d', strtotime($date));

			$month = date('m', strtotime($date));

			$year = date('Y', strtotime($date));

			switch ($month) {
				case "01":
					$month = 'января';
					break;
				case "02":
					$month = 'февраля';
					break;
				case "03":
					$month = 'марта';
					break;
				case "04":
					$month = 'апреля';
					break;
				case "05":
					$month = 'мая';
					break;
				case "06":
					$month = 'июня';
					break;
				case "07":
					$month = 'июля';
					break;
				case "08":
					$month = 'августа';
					break;
				case "09":
					$month = 'сентября';
					break;
				case "10":
					$month = 'октября';
					break;
				case "11":
					$month = 'ноября';
					break;
				case "12":
					$month = 'декабря';
					break;
			}

			if ($needYear) {
				return $day . ' ' . $month. ' '. $year;
			}

			return $day.' '.$month;
		}

		public static function isMobile() {

			$useragent = $_SERVER['HTTP_USER_AGENT'];

			return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
		}
	}