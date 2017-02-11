<?php


namespace Krujeva\RedisData {

	class Dealers extends Main {

		public static $key = 'market.dealerarea.';

		/*
		 * Получить список дилеров в регионе
		 */
		public static function getDealerArea($areaid) {

			$data = \Data\Redis::get(static::$key . $areaid);

			if ($data) {
				return $data;
			}

			$data = static::updateDealerArea($areaid);

			if (!$data) {
				return null;
			}

			return $data;
		}

		/*
		 * Обновить список дилеров по региону
		 */
		public static function updateDealerArea($areaid) {
			return static::loadData($areaid);
		}

		/*
		 * Обновить всех дилеров во всех регионах
		 */
		public static function loadData($areaid = null) {
			static::clearAllData($areaid);

			$options = [
				'fields' => '
					dealers.id,
					dealerregions.id as dealerregionid,
					dealerregions.areaid,
					dealerregions.deliveryday,
					dealerregions.minsum,
					dealers.name,
					cities.name as cityname,
					dealers.address,
					dealers.phone
					',
				'join' => [
					[
						'table' => 'market.dealers',
						'on' => 'dealerregions.dealerid = dealers.id'
					],
					[
						'table' => 'dict.cities',
						'on' => 'cities.id = dealers.cityid',
						'type' => 'left'
					]
				],
				'order' => 'dealerregions.deliveryday, dealerregions.minsum'
			];

			$q = new \Data\Query(null, $options);

			if ($areaid) {

				$q->where('dealerregions.areaid = $1', [$areaid]);
			}

			$dealers = \Krujeva\Data\DealerRegions::select($q->options());

			$regiondealers = [];

			foreach ($dealers as &$dealer) {

				if (!isset($regiondealers[$dealer['areaid']])) {

					$regiondealers[$dealer['areaid']] = [];
				}

				$dealer['brands'] = \Krujeva\Data\DealerBrands::select([
					'fields' => '
							dealerbrands.id as dealerbrandid,
							brands.name,
							brands.id,
							brands.code
						',
					'join' => [['table' => 'market.brands', 'on' => 'brands.id = dealerbrands.brandid']],
					'where' => 'dealerregionid = $1',
					'data' => [$dealer['dealerregionid']],
					'order' => 'brands.name'
				]);

				$regiondealers[$dealer['areaid']][] = $dealer;
			}

			$resdata = [];

			foreach ($regiondealers as $areaid => $regiondealer) {

				$key = static::$key . $areaid;

				$resdata[] = $regiondealer;

				$data = \JSON::stringify($regiondealer);

				\Data\Redis::set($key, $data, ['timeout' => 0]);
			}

			if (!count($resdata)) {
				return null;
			}

			return count($resdata) > 1 ? \JSON::stringify($resdata) : \JSON::stringify($resdata[0]);
		}



		private static function clearAllData($areaid = null) {

			if ($areaid) {

				\Data\Redis::del(static::$key . $areaid);

			} else {

				$keys = \Data\Redis::keys(static::$key . '*');

				foreach ($keys as $key) {
					\Data\Redis::del($key);
				}

			}

		}
	}

}