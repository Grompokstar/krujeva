<?php

namespace Data {
	class Build {
		public static function select($table, $options) {
			$query = sprintf('select %s from %s %s %s %s %s %s %s',
				static::fields($options),
				static::table($table, $options),
				static::join($options),
				static::where($options),
				static::group($options),
				static::order($options),
				static::offset($options),
				static::limit($options),
				static::forUpdate($options)
			);

			return $query;
		}

		public static function insert($table, $options) {
			$args = static::insertArgs($options);

			$query = sprintf('insert into %s(%s) values(%s) returning %s',
				$table,
				$args['fields'],
				$args['values'],
				static::returning($options)
			);

			return [$query, $args['data']];
		}

		public static function update($table, $options) {
			$args = static::updateArgs($options);

			$query = sprintf('update %s set %s %s returning %s',
				static::table($table, $options),
				$args['fields'],
				static::where($options),
				static::returning($options)
			);

			return [$query, $args['data']];
		}

		public static function remove($table, $options) {
			$query = sprintf('delete from %s %s returning %s',
				static::table($table, $options),
				static::where($options),
				static::returning($options)
			);

			return $query;
		}

		private static function table($name, $options) {
			if (isset($options['as'])) {
				$name .= ' as ' . $options['as'];
			}

			return $name;
		}

		private static function fields($options) {
			return isset($options['fields']) ? $options['fields'] : '*';
		}

		private static function join($options) {
			$joins = '';

			if (isset($options['join'])) {
				foreach ($options['join'] as $join) {
					$joins .= sprintf(' %s join %s on %s',
						isset($join['type']) ? $join['type'] : 'inner',
						$join['table'] . (isset($join['as']) ? ' as ' . $join['as'] : ''),
						$join['on']);
				}
			}

			return ltrim($joins);
		}

		private static function where($options) {
			return isset($options['where']) && $options['where'] ? 'where ' . $options['where'] : '';
		}

		private static function group($options) {
			return isset($options['group']) && $options['group'] ? 'group by ' . $options['group'] : '';
		}

		private static function order($options) {
			return isset($options['order']) && $options['order'] ? 'order by ' . $options['order'] : '';
		}

		private static function offset($options) {
			return isset($options['offset']) ? 'offset ' . $options['offset'] : '';
		}

		private static function limit($options) {
			return isset($options['limit']) ? 'limit ' . $options['limit'] : '';
		}

		private static function forUpdate($options) {
			return in_array('forupdate', $options, true) ? 'for update' : '';
		}

		private static function returning($options) {
			return isset($options['return']) ? $options['return'] : '*';
		}

		private static function insertArgs($options) {
			$fields = [];
			$values = [];
			$data = [];

			$converts = isset($options['convert']) ? $options['convert'] : [];
			$index = 0;

			if (isset($options['values']) && is_array($options['values'])) {
				foreach ($options['values'] as $field => $value) {
					$fields[] = $field;

					$convert = isset($converts[$field]) ? $converts[$field] : null;
					$withData = true;

					if ($convert) {
						if (strpos($convert, '$0') === false) {
							$arg = $convert;

							$withData = false;
						} else {
							$arg = str_replace('$0', '$' . (++$index), $convert);
						}
					} else {
						$arg = '$' . (++$index);
					}

					$values[] = $arg;

					if ($withData) {
						$data[] = $value;
					}
				}
			}

			return [
				'fields' => implode(', ', $fields),
				'values' => implode(', ', $values),
				'data' => $data
			];
		}

		private static function updateArgs($options) {
			$fields = [];
			$data = isset($options['data']) ? $options['data'] : [];

			$converts = isset($options['convert']) ? $options['convert'] : [];
			$index = count($data);

			if (isset($options['values']) && is_array($options['values'])) {
				foreach ($options['values'] as $field => $value) {
					$convert = isset($converts[$field]) ? $converts[$field] : null;
					$withData = true;

					if ($convert) {
						if (strpos($convert, '$0') === false) {
							$arg = $convert;

							$withData = false;
						} else {
							$arg = str_replace('$0', '$' . (++$index), $convert);
						}
					} else {
						$arg = '$' . (++$index);
					}

					$fields[] = "$field = $arg";

					if ($withData) {
						$data[] = $value;
					}
				}
			}

			return [
				'fields' => implode(',', $fields),
				'data' => $data
			];
		}
	}
}
 