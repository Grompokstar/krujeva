<?php

namespace Data {
	use System\Code;
	use System\Exception;

	class Record extends Item {
		/**
		 * @var Connection
		 */
		public static $connection;

		public static $container = 'records';

		public static $auto = ['id'];
		public static $hidden = null;

		public static $documentReference = [];
		public static $documentExclude = [];

		public static function select($options = []) {
			$query = new Query(static::$container, $options);

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			$result = isset($options['result']) ? $options['result'] : 'rows';
			$select = $query->select();
			$data = $query->data();

			switch ($result) {
				case 'query':
					return static::query($select, $data, $params);
				case 'row':
					$row = static::queryRow($select, $data, $params);

					static::adjust($row, ['type', 'hide']);

					return $row;
				case 'scalar':
					$row = static::queryRow($select, $data, $params);

					if ($row) {
						static::adjust($row, ['type', 'hide']);

						$row = array_shift($row);
					}

					return $row;
				default:
					$rows = static::queryRows($select, $data, $params);

					static::adjust($rows, ['array', 'type', 'hide']);

					return $rows;
			}
		}

		public static function first($options) {
			if (!isset($options['limit'])) {
				$options['limit'] = 1;
			}

			$records = static::select($options);

			return $records ? $records[0] : null;
		}

		public static function selectBy($fields, $options = []) {
			$condition = [];

			if (!isset($options['data'])) {
				$options['data'] = [];
			}

			$index = count($options['data']);

			foreach ($fields as $name => $value) {
				if ($value === null) {
					$condition[] = sprintf('%s is null', $name);
				} else {
					$condition[] = sprintf('%s = $%d', $name, ++$index);
					$options['data'][] = $value;
				}
			}

			$condition = implode(' and ', $condition);

			$options['where'] = isset($options['where']) ? sprintf('(%s) and %s', $options['where'], $condition) : $condition;

			return static::select($options);
		}

		public static function firstBy($fields, $options = []) {
			if (!isset($options['limit'])) {
				$options['limit'] = 1;
			}

			$options['result'] = 'row';

			return static::selectBy($fields, $options);
		}

		public static function count($options = []) {
			$options = array_merge($options, [
				'fields' => 'count(1) as count',
				'order' => null,
				'offset' => null,
				'limit' => null,
				'result' => 'scalar'
			]);

			return (int)static::select($options);
		}

		public static function get($id, $options = []) {
			if (!$id) {
				return null;
			}

			$alias = isset($options['as']) ? $options['as'] : static::$container;

			$data = isset($options['data']) ? $options['data'] : [];
			$data[] = $id;

			$where = [];

			if (isset($options['where'])) {
				$where[] = '(' . $options['where'] . ')';
			}

			$where[] = $alias . '.id = $' . count($data);

			$options = array_merge($options, [
				'where' => implode(' and ', $where),
				'data' => $data
			]);

			$records = static::select($options);

			return $records ? $records[0] : null;
		}

		public static function insert($record, $options = []) {
			$recordOptions = ['type', 'strict'];

			if (!in_array('raw', $options, true)) {
				$recordOptions[] = 'forupdate';
			}

			static::adjust($record, $recordOptions);

			$query = new Query(static::$container, $options);
			$query->values($record);

			$insert = $query->insert();
			$data = $query->data();

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			if (isset($options['noncalculate'])) {
				$params['noncalculate'] = $options['noncalculate'];
			}

			$row = static::queryRow($insert, $data, $params);

			static::adjust($row, ['type', 'hide']);

			return $row;
		}

		public static function update($record, $options = []) {
			if (!isset($record['id'])) {
				throw new Exception(sprintf('Update failed on %s', get_called_class()), Code::UpdateFailed);
			}

			$id = $record['id'];

			static::adjust($record, ['forupdate', 'type', 'strict']);

			$alias = (isset($options['as']) && $options['as']) ? $options['as'] . '.' : '';

			$query = new Query(static::$container, $options);
			$query->where("{$alias}id = $1", [$id])->values($record);

			$update = $query->update();
			$data = $query->data();

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			$row = static::queryRow($update, $data, $params);

			static::adjust($row, ['type', 'hide']);

			return $row;
		}

		public static function updateSet($record, $options = []) {
			static::adjust($record, ['forupdate', 'type', 'strict']);

			$query = new Query(static::$container, $options);
			$query->values($record);

			$update = $query->update();
			$data = $query->data();

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			$rows = static::queryRows($update, $data, $params);

			static::adjust($rows, ['array', 'type', 'hide']);

			return $rows;
		}

		public static function save($record, $options = []) {
			if ($record['id']) {
				return static::update($record, $options);
			} else {
				return static::insert($record, $options);
			}
		}

		public static function remove($id, $options = []) {
			$alias = (isset($options['as']) && $options['as']) ? $options['as'] . '.' : '';

			$query = new Query(static::$container, $options);
			$query->where("{$alias}id = $1", [$id]);

			$remove = $query->remove();
			$data = $query->data();

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			$row = static::queryRow($remove, $data, $params);

			static::adjust($row, ['type', 'hide']);

			return $row;
		}

		public static function removeSet($options = []) {
			$query = new Query(static::$container, $options);

			$remove = $query->remove();
			$data = $query->data();

			$params = [];

			if (isset($options['server'])) {
				$params['server'] = $options['server'];
			}

			$rows = static::queryRows($remove, $data, $params);

			static::adjust($rows, ['array', 'type', 'hide']);

			return $rows;
		}

		public static function isEmpty($record) {
			$empty = true;

			foreach (static::$fields as $field => $params) {
				$empty = $empty && static::isEmptyField($record, $field);
			}

			return $empty;
		}

		public static function isEmptyField($record, $field) {
			if (in_array($field, static::$auto)) {
				return true;
			}

			return !isset($record[$field]) || !trim($record[$field]);
		}

		public static function dropId(&$record) {
			unset($record['id']);
		}

		public static function diff($record1, $record2, $options = []) {
			if (!isset($options['exclude'])) {
				$options['exclude'] = [];
			}

			$options['exclude'] = array_merge($options['exclude'], static::$auto);

			return parent::diff($record1, $record2, $options);
		}

		public static function adjust(&$records, $options = []) {
			if (in_array('array', $options, true)) {
				unset($options[array_search('array', $options)]);

				foreach ($records as &$record) {
					static::adjust($record, $options);
				}
			} else {
				if (in_array('forupdate', $options, true)) {
					$options['exclude'] = static::$auto;
				}

				if (in_array('hide', $options, true) && static::$hidden) {
					$options['null'] = static::$hidden;
				}

				parent::adjust($records, $options);
			}
		}

		public static function query($query, $args = [], $params = []) {
			return static::$connection->query($query, $args, $params);
		}

		public static function queryRows($query, $args = [], $params = []) {
			return static::$connection->queryRows($query, $args, $params);
		}

		public static function queryRow($query, $args = [], $params = []) {
			return static::$connection->queryRow($query, $args, $params);
		}

		public static function queryScalar($query, $args = [], $params = []) {
			return static::$connection->queryScalar($query, $args, $params);
		}

		// Document behavior

		public static function createDocument($data = null) {
			$document = [];

			if ($data) {
				foreach (static::$fields as $name => $options) {
					$document[$name] = isset($data[$name]) ? $data[$name] : null;
				}

				foreach (static::$documentReference as $name => $options) {
					$document[$name] = isset($data[$name]) ? $data[$name] : ($options['type'] == Relation::Many ? [] : null);
				}
			} else {
				foreach (static::$fields as $name => $options) {
					$document[$name] = null;
				}

				foreach (static::$documentReference as $name => $options) {
					$document[$name] = $options['type'] == Relation::Many ? [] : null;
				}
			}

			return $document;
		}

		public static function getRecordOptions($record, $options = []) {
			return isset($options[$record]) ? array_merge($options, $options[$record]) : $options;
		}

		public static function documentReference($document, $name, $options = []) {
			$ref = static::$documentReference[$name];
			$records = isset($options['records']) ? $options['records'] : false;

			/**
			 * @var Record $class
			 */
			$class = $ref['record'];

			$fields = [];

			foreach ($ref['rel'] as $rel) {
				$fields[$rel['field']] = isset($rel['ref']) ? @$document[$rel['ref']] : $rel['value'];
			}

			$result = null;

			$refOptions = static::getRecordOptions($class, $options);

			switch ($ref['type']) {
				case Relation::Many:
					$result = $class::selectBy($fields, $refOptions);

					if (!$records) {
						foreach ($result as &$record) {
							$class::packDocument($record, $options);
						}
					}
					break;
				case Relation::One:
					$result = $class::firstBy($fields, $refOptions);

					if (!$records && $result) {
						$class::packDocument($result, $options);
					}
					break;
			}

			return $result;
		}

		public static function initReference($document, $name, &$reference) {
			$ref = static::$documentReference[$name];

			foreach ($ref['rel'] as $rel) {
				$reference[$rel['field']] = isset($rel['ref']) ? @$document[$rel['ref']] : $rel['value'];
			}
		}

		public static function eachReference(&$document, $callback) {
			foreach (static::$documentReference as $refName => $reference) {
				/**
				 * @var Record $class
				 */
				$class = $reference['record'];

				switch ($reference['type']) {
					case Relation::One:
						if (isset($document[$refName])) {
							$callback($document[$refName], $reference, $refName);
							$class::eachReference($document[$refName], $callback);
						}
						break;
					case Relation::Many:
						if (isset($document[$refName]) && is_array($document[$refName])) {
							foreach ($document[$refName] as &$item) {
								$callback($item, $reference, $refName);
								$class::eachReference($item, $callback);
							}
						}
						break;
				}
			}
		}

		public static function packDocument(&$document, $options = []) {
			foreach (static::$documentReference as $name => $params) {
				$document[$name] = static::documentReference($document, $name, $options);
			}
		}

		public static function selectDocuments($options) {
			$documents = static::select(static::getRecordOptions(static::className(), $options));

			foreach ($documents as &$document) {
				static::packDocument($document, $options);
			}

			return $documents;
		}

		public static function getDocument($id, $options = []) {
			$document = static::get($id, static::getRecordOptions(static::className(), $options));

			if ($document) {
				static::packDocument($document, $options);
			}

			return $document;
		}

		public static function insertDocument($data, $options = []) {
			if (!in_array('raw', $options)) {
				static::documentExclude($data);
			}

			txbegin();

			$document = static::insert($data, static::getRecordOptions(static::className(), $options));

			foreach (static::$documentReference as $name => $reference) {
				/**
				 * @var Record $class
				 */
				$class = $reference['record'];

				switch ($reference['type']) {
					case Relation::Many:
						$document[$name] = [];

						if (isset($data[$name])) {
							foreach ($data[$name] as $item) {
								foreach ($reference['rel'] as $rel) {
									$item[$rel['field']] = isset($rel['value']) ? $rel['value'] : @$document[$rel['ref']];
								}

								$document[$name][] = $class::insertDocument($item, $options);
							}
						}

						break;
					case Relation::One:
						$document[$name] = null;

						if (isset($data[$name])) {
							$item = $data[$name];

							foreach ($reference['rel'] as $rel) {
								$item[$rel['field']] = isset($rel['value']) ? $rel['value'] : @$document[$rel['ref']];
							}

							$document[$name] = $class::insertDocument($item, $options);
						}
						break;
				}
			}

			txcommit();

			return $document;
		}

		public static function updateDocument($data, $options = []) {
			$record = $data;

			static::adjust($record, ['strict']);

			txbegin();

			$document = static::get($data['id'], ['forupdate']);

			if (!$document) {
				throw new Exception('Document not found', Code::DocumentError);
			}

			$documentRecord = $document;

			static::documentExclude($record);
			static::documentExclude($documentRecord);

			$diff = static::diff($documentRecord, $record);

			if ($diff) {

				if (!in_array('not_update', $options)) {

					$diff['id'] = $document['id'];

					$documentRecord = static::update($diff, static::getRecordOptions(static::className(), $options));
					$document = array_merge($document, $documentRecord);
				}


			}

			/**
			 * @var Record $calledClass
			 */
			$calledClass = get_called_class();

			$updateRef = function ($data, $original, $name) use (&$document, $calledClass, $options) {
				$reference = $calledClass::$documentReference[$name];

				/**
				 * @var Record $class
				 */
				$class = $reference['record'];

				if ($data && !$original) {
					$calledClass::initReference($document, $name, $data);

					switch ($reference['type']) {
						case Relation::Many:
							$document[$name][] = $class::insertDocument($data, $options);
							break;
						case Relation::One:
							$document[$name] = $class::insertDocument($data, $options);
							break;
					}

				} else if (!$data && $original) {
					$class::removeDocument($original['id']);
				} else if ($data && $original) {
					$calledClass::initReference($document, $name, $data);

					switch ($reference['type']) {
						case Relation::Many:
							$document[$name][] = $class::updateDocument($data, $options);
							break;
						case Relation::One:
							$document[$name] = $class::updateDocument($data, $options);
							break;
					}
				}
			};

			$getIndex = function ($list, $id) {
				for ($i = 0, $count = count($list); $i < $count; $i++) {
					if (isset($list[$i]['id']) && $list[$i]['id'] == $id) {
						return $i;
					}
				}

				return null;
			};



			foreach (static::$documentReference as $name => $reference) {
				$related = static::documentReference($document, $name, ['records']);

				switch ($reference['type']) {
					case Relation::Many:
						if (!isset($data[$name]) || !is_array($data[$name])) {
							$data[$name] = [];
						}

						foreach ($related as $item) {
							$index = $getIndex($data[$name], $item['id']);

							if ($index !== null) {
								$dataItem = $data[$name][$index];
								array_splice($data[$name], $index, 1);
							} else {
								$dataItem = null;
							}

							$updateRef($dataItem, $item, $name);
						}

						foreach ($data[$name] as $item) {
							$updateRef($item, null, $name);
						}
						break;
					case Relation::One:
						if (!isset($data[$name])) {
							$data[$name] = null;
						}

						$updateRef($data[$name], $related, $name);
						break;
				}
			}

			txcommit();

			return $document;
		}

		public static function removeDocument($id) {
			return static::remove($id);
		}

		public static function documentExclude(&$document) {
			if (isset(static::$documentExclude)) {
				static::exclude($document, static::$documentExclude);
			}
		}

		public static function dropDocumentId(&$document) {
			static::dropId($document);

			static::eachReference($document, function (&$record, $reference) {
				/**
				 * @var Record $class
				 */
				$class = $reference['record'];

				$class::dropDocumentId($record);
			});
		}

		public static function packReference(&$items, $options = []) {

			if (!$items) {
				return;
			}

			if (is_array($options) && in_array('array', $options, true)) {
				unset($options[array_search('array', $options)]);

				$ids = [];

				$itemIndex = [];

				//@ids
				foreach ($items as $index => &$item) {

					foreach (static::$documentReference as $name => $params) {

						if (!isset($ids[$name])) {
							$ids[$name] = [];
						}

						if (!isset($itemIndex[$name])) {
							$itemIndex[$name] = [];
						}

						$ref = $params['rel'][0]['ref'];

						$itemIndex[$name][$item[$ref]] = $index;

						if ($item[$ref]) {

							$ids[$name][] = $item[$ref];
						}
					}
				}

				//@query
				foreach (static::$documentReference as $name => $params) {

					if (!isset($ids[$name])) {
						continue;
					}

					$class = $params['record'];

					$table = explode('.', $class::$container);

					$field = $params['rel'][0]['field'];

					$selectOptions = isset($options[$class]) ? $options[$class] : [];

					$referenceItems = $class::select(array_merge(['fields' => $table[1] . '.*', 'where' => $table[1] . '.' . $field . ' in (' . implode(',', $ids[$name]) . ')',], $selectOptions));

					foreach ($referenceItems as $referenceItem) {

						if (!isset($itemIndex[$name][$referenceItem[$field]])) {
							continue;
						}

						$i = $itemIndex[$name][$referenceItem[$field]];

						//@type
						if ($params['type'] == \Data\Relation::One) {

							$items[$i][$name] = $referenceItem;
						} else {

							if (!isset($items[$i][$name])) {

								$items[$i][$name] = [];
							}

							$items[$i][$name][] = $referenceItem;
						}
					}
				}
			} else {

				$ids = [];


				//@ids
				foreach (static::$documentReference as $name => $params) {

					if (!isset($ids[$name])) {
						$ids[$name] = [];
					}

					$ref = $params['rel'][0]['ref'];

					if ($items[$ref]) {

						$ids[$name][] = $items[$ref];
					}
				}

				//@query
				foreach (static::$documentReference as $name => $params) {

					if (!isset($ids[$name])) {
						continue;
					}

					if (isset($items[$name]) && $items[$name]) {
						continue;
					}

					$class = $params['record'];

					$table = explode('.', $class::$container);

					$field = $params['rel'][0]['field'];

					$selectOptions = isset($options[$class]) ? $options[$class] : [];

					$referenceItems = $class::select(array_merge(['fields' => $table[1] . '.*', 'where' => $table[1] . '.' . $field . ' in (' . implode(',', $ids[$name]) . ')',], $selectOptions));

					foreach ($referenceItems as $referenceItem) {

						//@type
						if ($params['type'] == \Data\Relation::One) {

							$items[$name] = $referenceItem;
						} else {

							if (!isset($items[$name])) {

								$items[$name] = [];
							}

							$items[$name][] = $referenceItem;
						}
					}
				}
			}
		}
	}
}

