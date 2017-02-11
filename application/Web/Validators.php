<?php

namespace Web {
	use System\Code;
	use System\Exception;

	trait Validators {
		protected function validateParamArray(&$param) {
			if (!is_array($param)) {
				$param = [];
			}

			return $param;
		}

		protected function validateParamDecodeJSON(&$param) {
			$param = \JSON::parse($param);

			return $param;
		}

		protected function validateParamKeysExist(&$param, $keys) {
			if (!is_array($param)) {
				$param = [];
			}

			foreach ($keys as $name) {
				if (!isset($param[$name])) {
					$param[$name] = null;
				}
			}

			return $param;
		}

		protected function validateParamType(&$param, $type) {
			if (!settype($param, $type)) {
				throw new Exception('Failed to cast', Code::TypeError);
			}
		}

		protected function validateParamRegExp(&$param, $pattern) {
			$param = (string)$param;

			if (!preg_match($pattern, $param)) {
				$param = null;
			}
		}

		protected function validateParamEmptyScalar(&$param) {
			if (!is_scalar($param) || !strlen($param)) {
				$param = null;
			}
		}
    }
}
 