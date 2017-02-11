<?php


namespace Krujeva\RedisData {

	class Products extends Main {

		public static $keyList = 'market.product.list.';
		public static $keyFull = 'market.product.full.';
		public static $keyCategory = 'market.product.category.'; //bit mask hash
		public static $keyPrice = 'market.dealerprice.';

		/*
		* Получить список товаров в категории
		*/
		public static function getProducts($categoryid, $dealerbrandid, $offset = 0, $limit = 100) {

			$hash = \Data\Redis::get(static::$keyCategory . $categoryid);

			if (!$hash) {
				//@todo = не нашли данные
				return null;
			}


			$offset = (int)$offset;

			$limit = (int)$limit;

			$ids = static::getIds($hash, $offset, $limit);

			$listitems = [];

			$prices = [];


			foreach ($ids as $id) {

				$item = \Data\Redis::get(static::$keyList. $id);

				if ($item) {

					$listitems[] = $item;
				}

				$price = \Data\Redis::get(static::$keyPrice . $dealerbrandid . '.' . $id);

				if ($price) {

					$prices[] = $price;
				}
			}

			return ['listitems' => $listitems, 'prices' => $prices];
		}

		public static function getIds($valueRedis, $offset, $limit) {

			$result = [];

			$unlimit = false;

			if (!$limit) {
				$unlimit = true;
			}

			$offsetCount = 0;

			$offsetLimit = 0;


			for ($i = 0; $i < mb_strlen($valueRedis, 'utf-8'); $i++) {
				$tmp = ord($valueRedis[$i]);

				for ($j = 0; $j < 8; $j++) {

					if (($tmp & (1 << $j)) != 0) {

						if ($unlimit) {

							$result[] = (8 * $i + (7 - $j));

						} else {

							if ($offsetCount >= $offset) {

								if ($offsetLimit < $limit) {

									$result[] = (8 * $i + (7 - $j));

									$offsetLimit++;

								} else {

									break;
								}

							} else {
								$offsetCount++;
							}

						}
					}
				}
			}

			return $result;
		}


		public static function clearCategory($productid, $categoryid) {
			\Data\Redis::setBit(static::$keyCategory . $categoryid, $productid, 0);
		}

		/*
		 * Обновить товар
		 */
		public static function updateProductById($productid, $categoryid) {

			return static::loadData($productid, $categoryid);
		}

		/*
		 * Обновить цены у бренда
		 */
		public static function updatePriceByDealerBrandId($dealerbrandid, $prices) {

			//clear
			$keys13 = \Data\Redis::keys(static::$keyPrice. $dealerbrandid . '*');

			foreach ($keys13 as $key1) {

				\Data\Redis::del($key1);
			}

			foreach ($prices as $price) {

				$data = [
					'productid' => $price['productid'],
					'price' => $price['price']
				];

				//@set price dealer data
				\Data\Redis::set(static::$keyPrice . $dealerbrandid . '.' . $price['productid'], \JSON::stringify($data), ['timeout' => 0]);
			}
		}

		/*
		 * Обновить все товары во всеx категориях
		 */
		public static function loadData($productid = null, $categoryid = null) {
			static::clearAllData($productid, $categoryid);

			$options = [
				'fields' => '
						id,
						productcategoryid,
						brandid
					',
			];

			$q = new \Data\Query(null, $options);

			if ($productid) {

				$q->where('id = $1', [$productid]);
			}

			$products = \Krujeva\Data\Products::select($q->options());

			\Krujeva\Data\Products::build($products, ['array']);

			$reslistdata = [];

			foreach ($products as $product) {

				$product = \Krujeva\Data\Products::packItem($product['id']);

				$listitem = static::makeListItem($product);

				if (!$listitem) {
					continue;
				}

				$fullItem = static::makeFullItem($product);

				if (!$fullItem) {
					continue;
				}

				$reslistdata[] = $listitem;

				//@set hash bit
				\Data\Redis::setBit(static::$keyCategory . $product['productcategoryid'], $product['id'], 1);

				//@set list item
				\Data\Redis::set(static::$keyList. $product['id'], \JSON::stringify($listitem), ['timeout' => 0]);

				//@set full item
				\Data\Redis::set(static::$keyFull . $product['id'], \JSON::stringify($fullItem), ['timeout' => 0]);


				//@elastic product
				$elasticProduct = static::makeElasticItem($product);
				\Krujeva\Elastic\Product::updateProduct($elasticProduct);
			}

			//@ цены тоже надо загрузить в редис
			if (!$productid) {

				$prices = \Krujeva\Data\DealerPrices::select();

				foreach ($prices as $price) {

					$data = [
						'productid' => $price['productid'],
						'price' => $price['price'],
					];

					//@set price dealer data
					\Data\Redis::set(static::$keyPrice. $price['dealerbrandid'].'.' . $price['productid'], \JSON::stringify($data), ['timeout' => 0]);
				}
			}

			if (!count($reslistdata)) {
				return null;
			}

			return count($reslistdata) > 1 ? \JSON::stringify($reslistdata) : \JSON::stringify($reslistdata[0]);
		}

		public static function makeListItem($item) {

			$data = [
				'id' => $item['id']
			];

			$name = '';

			$volume = '';

			$bonusnayacena = '';

			if (isset($item['values']) && is_array($item['values'])) {

				foreach ($item['values'] as $property) {
					switch ($property['code']) {
						case 'name':
							$name = $property['value'];
							break;
						case 'volume':
							$volume = $property['value'];
							break;
						case 'bonusnayacena':
							$bonusnayacena = $property['value'];
							break;
					}
				}
			}

			if (!$name) {
				return null;
			}

			if ($volume) {
				$name .= ', '. $volume.'мл';
			}

			if ($bonusnayacena) {
				$data['bonusnayacena'] = $bonusnayacena;
			}

			$data['name'] = $name;

			//@photo
			if (isset($item['photo']) && $item['photo']) {

				$data['photopath'] = $item['photo']['relativepath'];

				$data['photoname'] = $item['photo']['avatarname'];
			}

			return $data;
		}

		public static function makeElasticItem($item) {

			$data = [
				'id' => $item['id'],
				'brandid' => $item['brandid'],
				'productcategoryid' => $item['productcategoryid']
			];

			$name = '';

			$volume = '';

			if (isset($item['values']) && is_array($item['values'])) {

				foreach ($item['values'] as $property) {
					switch ($property['code']) {
						case 'name':
							$name = $property['value'];
							break;
						case 'volume':
							$volume = $property['value'];
							break;
					}
				}
			}

			if (!$name) {
				return null;
			}

			if ($volume) {
				$name .= ', ' . $volume . 'мл';
			}

			$data['name'] = $name;

			//@photo
			if (isset($item['photo']) && $item['photo']) {

				$data['photopath'] = $item['photo']['relativepath'];

				$data['photoname'] = $item['photo']['avatarname'];
			}

			return $data;
		}

		public static function makeFullItem($item) {

			$data = [
				'id' => $item['id'],
                'brandid' => $item['brandid']
			];

			$name = '';

			$volume = '';

			$bonusnayacena = '';

			if (isset($item['values']) && is_array($item['values'])) {

				foreach ($item['values'] as $property) {

					if (!isset($property['value'])) {
						continue;
					}

					switch ($property['code']) {
						case 'name':
							$name = $property['value'];
							break;
						case 'volume':
							$volume = $property['value'];
							break;
						case 'description':
							$data['description'] = $property['value'];
							break;
						case 'usedescription':
							$data['usedescription'] = $property['value'];
							break;
						case 'bonusnayacena':
							$bonusnayacena = $property['value'];
							break;
					}
				}
			}

			if (!$name) {
				return null;
			}

			if ($volume) {
				$name .= ', ' . $volume . 'мл';
			}

			if ($bonusnayacena) {
				$data['bonusnayacena'] = $bonusnayacena;
			}

			$data['name'] = $name;

			//@photo
			if (isset($item['photo']) && $item['photo']) {

				$data['photopath'] = $item['photo']['relativepath'];

				$data['photoname'] = $item['photo']['name'];

				$data['photowidth'] = $item['photo']['width'];

				$data['photoheight'] = $item['photo']['height'];
			}

			return $data;
		}



		private static function clearAllData($productid = null, $categoryid = null) {

			if ($productid && $categoryid) {

				//@elastic remove
				\Krujeva\Elastic\Product::removeProduct($productid);

				\Data\Redis::del(static::$keyFull . $productid);

				\Data\Redis::del(static::$keyList . $productid);

				\Data\Redis::setBit(static::$keyCategory . $categoryid, $productid, 0);

			} else {

				$keys = \Data\Redis::keys(static::$keyFull . '*');

				foreach ($keys as $key) {
					\Data\Redis::del($key);
				}

				$keys11 = \Data\Redis::keys(static::$keyList . '*');

				foreach ($keys11 as $key1) {
					\Data\Redis::del($key1);
				}

				$keys12 = \Data\Redis::keys(static::$keyCategory . '*');

				foreach ($keys12 as $key1) {
					\Data\Redis::del($key1);
				}

				$keys13 = \Data\Redis::keys(static::$keyPrice . '*');

				foreach ($keys13 as $key1) {
					\Data\Redis::del($key1);
				}

				//remove all products
				\Krujeva\Elastic\Product::removeAll();
			}

		}
	}

}