<?php


namespace Krujeva\RedisData {

	class BrandCategory extends Main {

		public static $key = 'market.brandcategory.';
		public static $key1 = 'market.brandcategory.version.';

		/*
		 * Получить список категорий бренда
		 */
		public static function getBrandCategoryByBrandId($brandid) {

			$data = \Data\Redis::get(static::$key . $brandid);

			$version = (int)\Data\Redis::get(static::$key1 . $brandid);

			if ($data && $version) {

				return ['version' => $version, 'items' => \JSON::parse($data)];
			}

			$data = static::updateBrandCategoryByBrandId($brandid);

			if (!$data) {
				return null;
			}

			return \JSON::parse($data);
		}


		/*
		 * Обновить список категорий бренда
		 */
		public static function updateBrandCategoryByBrandId($brandid) {
			return static::loadData($brandid);
		}

		/*
		 * Обновить все категории во все брендах
		 */
		public static function loadData($brandid = null) {
			static::clearAllData($brandid);

			$options = [
				'fields' => '
						id,
						name,
						brandid
					',
				'order' => 'brandid, name',
				'where' => 'parentid is null'
			];

			$q = new \Data\Query(null, $options);

			if ($brandid) {

				$q->where('brandid = $1', [$brandid]);
			}

			$categories = \Krujeva\Data\ProductCategories::select($q->options());

			$brandcategories = [];

			foreach ($categories as &$category) {

				$brandid = $category['brandid'];

				unset($category['brandid']);

				static::setChildrens($category);

				if (!isset($brandcategories[$brandid])) {
					$brandcategories[$brandid] = [];
				}

				$brandcategories[$brandid][] = $category;
			}

			$resdata = [];

			foreach ($brandcategories as $brandid => $categories) {

				$version = rand(1, 2000);

				$versionkey = static::$key1 . $brandid;

				$key = static::$key . $brandid;

				$resdata[] = ['version' => $version, 'items' => $categories];

				$data = \JSON::stringify($categories);

				\Data\Redis::set($key, $data, ['timeout' => 0]);

				\Data\Redis::set($versionkey, $version, ['timeout' => 0]);
			}

			if (!count($resdata)) {
				return null;
			}

			return count($resdata) > 1 ? \JSON::stringify($resdata) : \JSON::stringify($resdata[0]);
		}

		private static function setChildrens(&$category) {

			$category['childrens'] = \Krujeva\Data\ProductCategories::select([
				'fields' => '
						id,
						name
					',
				'where' => 'parentid = $1',
				'data' => [$category['id']],
				'order' => 'name'
			]);

			foreach ($category['childrens'] as &$cat) {
				static::setChildrens($cat);
			}

			if (!count($category['childrens'])) {

				unset($category['childrens']);
			}
		}



		private static function clearAllData($brandid = null) {

			if ($brandid) {

				\Data\Redis::del(static::$key . $brandid);
				\Data\Redis::del(static::$key1 . $brandid);

			} else {

				$keys = \Data\Redis::keys(static::$key . '*');

				foreach ($keys as $key) {
					\Data\Redis::del($key);
				}


				$keys11 = \Data\Redis::keys(static::$key1 . '*');

				foreach ($keys11 as $key1) {
					\Data\Redis::del($key1);
				}

			}

		}
	}

}