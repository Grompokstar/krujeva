<?php

namespace Search {
	use System\Code;
	use System\Exception;

	class Elastic {
		public static $url = 'http://localhost:9200';

		public static $Headers = [
			'Accept: */*'
		];

		public static function init($options = []) {
			if (isset($options['url'])) {
				static::$url = $options['url'];
			}
		}

		public static function create($index, $options = []) {
			return static::request('PUT', static::url($index), $options);
		}

		public static function settings($index, $settings) {
			return static::request('PUT', static::url($index, null, '/_settings'), $settings);
		}

		public static function mapping($index, $type, $settings) {
			return static::request('PUT', static::url($index, $type, '/_mapping'), $settings);
		}

		public static function get($index, $type, $id) {
			return static::request('GET', static::url($index, $type, "/$id/_source"));
		}

		public static function insert($index, $type, $record) {
			if (!isset($record['id']) || !$record['id']) {
				return false;
			}

			return static::request('PUT', static::url($index, $type, "/{$record['id']}"), $record);
		}

		public static function remove($index, $type = null, $id = null) {
			return static::request('DELETE', static::url($index, $type, $id ? "/$id" : null));
		}

		public static function count($index, $type, $query) {
			return static::request('GET', static::url($index, $type, '/_count'), $query);
		}

		public static function query($index, $type, $query) {
			return static::request('POST', static::url($index, $type, '/_search'), $query);
		}

		public static function url($index, $type = null, $operation = null) {
			$url = '/' . $index;

			if ($type) {
				$url .= '/' . $type;
			}

			if ($operation) {
				$url .= $operation;
			}

			return $url;
		}

		public static function request($method, $url, $data = null) {
			$url = static::$url . $url;

			if ($data) {
				$data = \JSON::stringify($data);
			}

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

			if ($data) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}

			curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/x-www-form-urlencoded']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($curl);

			curl_close($curl);
			return \JSON::parse($response);
		}

		public static function explodeTerm($term, $options = []) {
			$minLength = option('minLength', $options, 3);
			$words = preg_split('/\s+/', trim($term));
			$keywords = [];

			foreach ($words as $word) {
				$word = trim($word);

				if (mb_strlen($word) >= $minLength) {
					$keywords[] = $word;
				}
			}

			return $keywords;
		}
	}
}
 
