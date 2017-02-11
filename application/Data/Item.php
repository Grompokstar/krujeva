<?php

namespace Data {
	class Item {
		public static $fields = [
			'id' => ['int']
		];

		public static function className() {
			return get_called_class();
		}

		public static function create($data = null) {
			$item = [];

			if ($data) {
				foreach (static::$fields as $name => $options) {
					$item[$name] = isset($data[$name]) ? $data[$name] : null;
				}
			} else {
				foreach (static::$fields as $name => $options) {
					$item[$name] = null;
				}
			}

			return $item;
		}

		public static function adjust(&$item, $options = []) {
			if (!$item) {
				return;
			}

			if (in_array('strict', $options, true)) {
				$item = array_intersect_key($item, static::$fields);
			}

			if (in_array('type', $options, true)) {
				foreach ($item as $name => &$value) {
					if (isset(static::$fields[$name])) {
						$type = static::$fields[$name][0];

						switch ($type) {
							default:
								if ($value !== null) {
									settype($value, $type);
								}
						}
					}
				}
			}

			if (isset($options['exclude'])) {
				foreach ($options['exclude'] as $field) {
					unset($item[$field]);
				}
			}

			if (isset($options['null'])) {
				foreach ($options['null'] as $field) {
					if (isset($item[$field])) {
						$item[$field] = null;
					}
				}
			}

			if (isset($options['callback']) && is_callable($options['callback'])) {
				$options['callback']($item);
			}
		}

		public static function diff($item1, $item2, $options = []) {
			$diff = [];

			$fields = array_keys(static::$fields);

			if (isset($options['exclude'])) {
				$fields = array_diff($fields, $options['exclude']);
			}

			foreach ($fields as $name) {
				if (!isset($item1[$name]) && !isset($item2[$name])) {
					continue;
				}

				if (!isset($item1[$name])) {
					$diff[$name] = $item2[$name];
				} else if (!isset($item2[$name])) {
					$diff[$name] = null;
				} else if ($item1[$name] != $item2[$name]) {
					$diff[$name] = $item2[$name];
				}
			}

			return $diff;
		}

		public static function diffSet($set1, $set2, $options = []) {
			$insert = [];
			$remove = [];
			$update = [];
			$unmodified = [];

			$key = 'id';

			$set1Keys = [];
			$set2Items = [];

			foreach ($set1 as $item) {
				$set1Keys[] = isset($item[$key]) ? $item[$key] : null;
			}

			foreach ($set2 as $item) {
				if (!isset($item[$key]) || array_search($item[$key], $set1Keys) === false) {
					$insert[] = $item;
				} else {
					$set2Items[$item[$key]] = $item;
				}
			}

			foreach ($set1 as $item) {
				if (!isset($set2Items[$item[$key]])) {
					$remove[] = $item;
				} else {
					if ($diff = static::diff($item, $set2Items[$item[$key]], $options)) {
						$diff[$key] = $item[$key];
						$update[] = $diff;
					} else {
						$unmodified[] = $item;
					}
				}
			}

			return [$insert, $remove, $update, $unmodified];
		}

		public static function exclude(&$item, $fields) {
			$item = array_diff_key($item, array_fill_keys($fields, true));
		}

		public static function slice(&$item, $fields) {
			$item = array_intersect_key($item, array_fill_keys($fields, true));
		}

		public static function translate($data, $tr) {
			$item = [];

			foreach ($tr as $from => $to) {
				if (array_key_exists($from, $data)) {
					$item[$to] = $data[$from];
				}
			}

			return $item;
		}

		public static function process(&$items, $options, $callback) {
			if ($items) {
				if (is_array($options) && in_array('array', $options, true)) {
					unset($options[array_search('array', $options)]);

					foreach ($items as &$item) {
						$callback($item, $options);
					}
				} else {
					$callback($items, $options);
				}
			}
		}

		public static function toJSON(&$item, $field, $force = false) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::toJSON($item, $fieldName, $force)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field]) || $force) {
						if (!is_string(@$item[$field])) {
							$item[$field] = \JSON::stringify(@$item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function fromJSON(&$item, $field) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::fromJSON($item, $fieldName)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field])) {
						if (is_string($item[$field])) {
							$item[$field] = \JSON::parse($item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function toPGIntArray(&$item, $field, $force = false) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::toPGIntArray($item, $fieldName, $force)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field]) || $force) {
						if (!is_string(@$item[$field])) {
							$item[$field] = pgIntArrayEncode(@$item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function fromPGIntArray(&$item, $field, $force = false) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::fromPGIntArray($item, $fieldName, $force)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field]) || $force) {
						if (!is_array(@$item[$field])) {
							$item[$field] = pgIntArrayDecode(@$item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function toPGArray(&$item, $field, $force = false) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::toPGArray($item, $fieldName, $force)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field]) || $force) {
						if (!is_string(@$item[$field])) {
							$item[$field] = pgArrayEncode(@$item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function fromPGArray(&$item, $field) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::fromPGArray($item, $fieldName)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field])) {
						if (!is_array($item[$field])) {
							$item[$field] = pgArrayDecode($item[$field]);
						}

						$result = true;
					}
				}
			}

			return $result;
		}

		public static function toInt(&$item, $field) {
			$result = false;

			if ($item) {
				if (is_array($field)) {
					$result = [];

					foreach ($field as $fieldName) {
						if (static::toInt($item, $fieldName)) {
							$result[] = $fieldName;
						}
					}
				} else {
					if (isset($item[$field])) {
						if (!is_int($item[$field])) {
							$item[$field] = (int)$item[$field];
						}

						$result = true;
					}
				}
			}

			return $result;
		}
	}
}
