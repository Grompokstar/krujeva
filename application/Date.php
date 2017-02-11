<?php

namespace {
	class Date {
		public static function datetime($timestamp = null) {
			return date('Y-m-d H:i:s', $timestamp);
		}

		public function date($timestamp = null) {
			return date('Y-m-d', $timestamp);
		}

		public static function time($timestamp = null) {
			return date('H:i:s', $timestamp);
		}

		public static function parse($datetime) {
			//$calc = preg_split("/(\\+|\\-)( )*(\d+)/", /*"\${1}\${2}\${3}\${4}\${5}",*/ $datetime);

			$datetime = preg_replace('/\\+.*$/', '', $datetime);

			return strtotime($datetime);
		}
	}
}
 