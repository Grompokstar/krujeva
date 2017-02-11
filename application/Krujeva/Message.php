<?php

namespace Krujeva {

	class Message {

		private static function eventCallback($record, $key) {

			$callback = <<<JS
function validate(context, data) {
	return check(context, '$key', AccessMode.Read);
}
JS;
			return $callback;
		}

		private static function dealerOrderCallback($dealerid) {

			$roleid = \Krujeva\Roles::DealerUser;

			$callback = <<<JS
function validate(context, data) {
	return context['role']['id'] == $roleid && context['userProfile']['dealerid'] == $dealerid;
}
JS;
			return $callback;
		}

		private static function bonusOrderCallback() {

			$roleid = \Krujeva\Roles::BonusUser;

			$callback = <<<JS
function validate(context, data) {
	return context['role']['id'] == $roleid;
}
JS;
			return $callback;
		}

		public static function event($event, $record, $key) {
			\Message::event($event, ['id' => $record['id']], static::eventCallback($record, $key));
		}

		public static function dealerNewOrder($orderid, $dealerid) {
			\Message::event('Krujeva.Orders.Insert', ['id' => $orderid], static::dealerOrderCallback($dealerid));
		}

        public static function dealerNotNew($orderid, $neworders, $dealerid) {
            \Message::event('Krujeva.Orders.NotNew', ['id' => $orderid, 'neworders' => $neworders], static::dealerOrderCallback($dealerid));
        }

		public static function dealerOrderCancelled($orderid, $dealerid) {
			\Message::event('Krujeva.Orders.Cancelled', ['id' => $orderid], static::dealerOrderCallback($dealerid));
		}

		public static function dealerOrderConfirmed($orderid, $dealerid) {

			\Message::event('Krujeva.Orders.Confirmed', ['id' => $orderid], static::dealerOrderCallback($dealerid));
		}





		public static function bonusOrderCancelled($orderid) {

			\Message::event('Krujeva.BonusOrders.Cancelled', ['id' => $orderid], static::bonusOrderCallback());
		}

		public static function bonusNotNew($orderid, $neworders) {

			\Message::event('Krujeva.BonusOrders.NotNew', ['id' => $orderid, 'neworders' => $neworders], static::bonusOrderCallback());
		}

		public static function bonusOrderConfirmed($orderid) {

			\Message::event('Krujeva.BonusOrders.Confirmed', ['id' => $orderid], static::bonusOrderCallback());
		}

	}
}