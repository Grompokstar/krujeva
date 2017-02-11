<?php

namespace Krujeva\Elastic {

	class Product extends \Search\Elastic {

		public static function setSettings() {

			$options = [
				'settings' => [

					'analysis' => [
						'analyzer' => [
							'my_analyzer' => [
								'type' => 'custom',
								'tokenizer' => 'standard',
								'filter' => ["lowercase", "russian_morphology"]
							]
						]
					],
				]
			];

			static::create('marketnew2', $options);

			$options = [
				'products' => [
					'_all' => [
						'analyzer' => 'russian_morphology'
					],
					'properties' => [
						'name' => [
							'type' => 'string',
							'analyzer' => 'my_analyzer'
						],
						'description' => [
							'type' => 'string',
							'analyzer' => 'my_analyzer'
						],
					]
				]
			];

			return static::mapping('marketnew2', 'products', $options);
		}

		public static function getProduct($id) {
			return static::get('marketnew2', 'products', $id);
		}

		public static function removeProduct($id) {
			return static::remove('marketnew2', 'products', $id);
		}

		public static function removeAll() {
			return static::remove('marketnew2', 'products');
		}

		public static function updateProduct($item) {

			if (!$item) {
				return null;
			}

			return static::insert('marketnew2', 'products', $item);
		}

		public static function search($value, $brandid, $productcategoryid = null) {
			$term = preg_replace('/\s+/', ' ', str_replace([',', '.', '-'], ' ', $value));

			$term = explode(' ', $term);

			$brandid = (int)$brandid;

			//$term = 'brandid:'. $brandid.' AND (name:(*' . implode(' AND *', $term) . '*) OR description:(*' . implode(' AND *', $term) . '*))';
			$term = 'brandid:'. $brandid.' AND (name:(*' . implode(' AND *', $term) . '*))';

			$hits = \Search\Elastic::query('marketnew2', 'products', [
				'query' => [
					'query_string' => [
						'query' => $term,
						'analyze_wildcard' => true
					]
				],
				'size' => 30
			]);

			if (!isset($hits['hits'])) {
				return [];
			}

			if (!isset($hits['hits']['hits'])) {
				return [];
			}

			$hits = $hits['hits']['hits'];

			$result = [];

			foreach ($hits as $hit) {

				if (!isset($hit['_source'])) {
					continue;
				}

				unset($hit['_source']['brandid']);
				unset($hit['_source']['productcategoryid']);
				//unset($hit['_source']['description']);

				$result[] = $hit['_source'];
			}

			return $result;
		}

	}

}
