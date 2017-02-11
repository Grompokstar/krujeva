<?php

namespace Krujeva\RedisData {

	class HairOrdersHistory extends Main {

		public static $keyList = 'market.order.history.list.'; //list
		public static $keyFull = 'market.order.history.full.';
		public static $keyRange = 'market.order.history.range.';
		public static $maxItems = 30;

		public static function updateOrder($userid, $orderid, $insert = false) {

			if ($insert) {
				return static::loadData($userid, $orderid);
			}

			$keys = \Data\Redis::lRange(static::$keyRange . $userid, 0, static::$maxItems);

			$needUpdate = false;

			if ($keys && is_array($keys)) {

				foreach ($keys as $key) {

					if ($key == $orderid) {
						$needUpdate = true;
						break;
					}
				}
			}

			if (!$needUpdate) {
				return;
			}

			return static::loadData($userid, $orderid);
		}

		/*
		 * Обновить историю парикмахеров - заказы
		 */
		public static function loadData($userid = null, $orderid = null) {

			static::clearAllData($userid, $orderid);

			$selectData = ['roleid' => \Krujeva\Roles::HairUser];

			if ($userid) {

				$selectData['id'] = $userid;
			}

			$reslistdata = [];

			$hairUsers = \Security\Data\Users::selectBy($selectData);

			foreach ($hairUsers as $hairUser) {

				$q = new \Data\Query(null, [
					'fields' => '
						orders.id,
						orders.status,
						orders.localcreateddatetime,
						orders.localdeliverydate,
						orders.totalprice,

						dealers.name as dealername,
						dealers.phone as dealerphone,

						cities.name as barbershopcityname,
						barbershop.address as barbershopaddress,
						barbershop.salonname as barbershopsalonname,
						(
							select array_to_json(array_agg(row)) from (select productid, count, price from market.orderproducts where orderid = orders.id ) row
						) as orderproducts
					',
					'join' => [
						[
							'table' => 'market.dealers',
							'on' => 'dealers.id = orders.dealerid'
						],
						[
							'table' => 'market.barbershop',
							'on' => 'barbershop.id = orders.barbershopid'
						],
						[
							'table' => 'dict.cities',
							'on' => 'cities.id = barbershop.cityid'
						]
					],
					'where' => 'orders.clientid = $1',
					'data' => [$hairUser['id']],
					'order' => 'orders.createddatetime desc',
					'limit' => static::$maxItems
				]);

				if ($orderid) {

					$q->where('orders.id = $1', [$orderid]);
				}

				$orders = \Krujeva\Data\Orders::select($q->options());

				$orders = array_reverse($orders);

				foreach ($orders as $order) {

					$year = date('Y', strtotime($order['localcreateddatetime']));

					$order['localcreateddatetime'] = \Utils::localDate($order['localcreateddatetime']).' '. $year;

					$order['orderproducts'] = \JSON::parse($order['orderproducts']);

					if ($order['localdeliverydate']) {

						$order['localdeliverydate'] = \Utils::localDate($order['localdeliverydate']);
					}


					$listitem = static::formListItem($order);

					//@set full item
					\Data\Redis::set(static::$keyFull . $order['id'], \JSON::stringify($order), ['timeout' => 0]);

					//@set list item
					\Data\Redis::set(static::$keyList . $order['id'], \JSON::stringify($listitem), ['timeout' => 0]);

					//@set range
					static::addRange($hairUser['id'], $order['id']);

					$reslistdata[] = $listitem;
				}
			}

			if (!count($reslistdata)) {
				return null;
			}

			return count($reslistdata) > 1 ? \JSON::stringify($reslistdata) : \JSON::stringify($reslistdata[0]);
		}

		private static function formListItem($item) {

			return [
				'id' => $item['id'],
				'status' => $item['status'],
				'totalprice' => $item['totalprice'],
				'localcreateddatetime' => $item['localcreateddatetime'],
				'localdeliverydate' => $item['localdeliverydate'],
			];
		}

		//добавляем - перетирая старую заявку если лимита достигли
		private static function addRange($userid, $orderid) {

			$count = \Data\Redis::lLen(static::$keyRange . $userid);

			if ($count > static::$maxItems) {

				//1 получаем лишние итемы
				$keys = \Data\Redis::lRange(static::$keyRange . $userid, static::$maxItems, static::$maxItems + 100);

				//2 удаляем итемы
				if ($keys && is_array($keys)) {

					foreach ($keys as $key) {

						\Data\Redis::del(static::$keyFull . $key);

						\Data\Redis::del(static::$keyList . $key);
					}
				}

				//очищаем список
				\Data\Redis::lTrim(static::$keyRange . $userid, 0, static::$maxItems - 1);
			}


			$keys = \Data\Redis::lRange(static::$keyRange . $userid, 0, static::$maxItems);

			$issetOrder = false;

			if ($keys && is_array($keys)) {

				foreach ($keys as $key) {

					if ($key == $orderid) {
						$issetOrder = true;
						break;
					}
				}
			}

			if (!$issetOrder) {

				\Data\Redis::lPush(static::$keyRange . $userid, $orderid);
			}
		}



		private static function clearAllData($userid = null, $orderid = null) {

			if ($userid && $orderid) {

				\Data\Redis::del(static::$keyFull. $orderid);

				\Data\Redis::del(static::$keyList. $orderid);

			} else {

				$keys = \Data\Redis::keys(static::$keyFull . '*');

				foreach ($keys as $key) {
					\Data\Redis::del($key);
				}

				$keys11 = \Data\Redis::keys(static::$keyList . '*');

				foreach ($keys11 as $key1) {
					\Data\Redis::del($key1);
				}

				$keys22 = \Data\Redis::keys(static::$keyRange . '*');

				foreach ($keys22 as $key2) {
					\Data\Redis::del($key2);
				}

			}

		}

	}
}