<?php

namespace Krujeva {

	class SMS {

		private static $configuration = [];

		public static function init($options = []) {
			static::$configuration = $options;
		}

		public static function dealerNewOrder($phone, $totalprice) {

			$text = 'Новый заказ на сумму '. $totalprice .' руб. Подтвердите на kvik-club.ru';

			return static::sendSMS($phone, $text);
		}

		public static function dealerRegistration($phone, $password) {

			$text = 'Вас зарегистрировали в системе КВИК! Зайдите на страницу http://partner.kvik-club.ru Логин: '. $phone.' Пароль: '. $password;

			return static::sendSMS($phone, $text);
		}


		public static function hairCancelledOrder($phone, $orderid) {

			$text = 'Ваш заказ №' . $orderid . ' отклонен';

			return static::sendSMS($phone, $text);
		}

		public static function hairConfirmedOrder($phone, $orderid, $price, $deliverydate) {

			$text = 'Заказ №' . $orderid . ' подтвержден. Сумма '. $price.'руб. Доставка '.\Utils::localDate($deliverydate);

			return static::sendSMS($phone, $text);
		}

		public static function hairConfirmedBonusOrder($phone, $orderid, $price, $deliverydate) {

			$text = 'Заказ №' . $orderid . ' подтвержден. Сумма ' . $price . 'баллов. Доставка ' . \Utils::localDate($deliverydate);

			return static::sendSMS($phone, $text);
		}

		public static function hairBonus($phone, $bonus) {

			$bal = \Utils::makeTitle($bonus, [' балл', ' балла', ' баллов']);

			$text = 'Вам начислено ' . $bonus . $bal . ' за заказ';

			return static::sendSMS($phone, $text);
		}


		public static function verifyPhoneSMS($phone, $password) {

			$text = $password . ' — Ваш пароль для подтверждения регистрации';

			return static::sendSMS($phone, $text);
		}

		public static function forgotPhoneSMS($phone, $password) {

			$text = $password . ' — Ваш пароль';

			return static::sendSMS($phone, $text);
		}

		public static function sendSMS($phone, $text) {

			if (!static::isInit()) {
				return null;
			}

			$phone = static::cleanPhone($phone);

			$options = [
				'sender' => static::$configuration['sender'],
				'phone' => $phone,
				'text' => $text
			];

			$sms = static::sendHttp('send', $options);

			//array(1) { [0]=> string(19) "accepted;1652497293" }

			return (bool)$sms;
		}

		public static function balance() {

			if (!static::isInit()) {
				return null;
			}

			$balance = static::sendHttp('balance');

			if (count($balance)) {
				$balance = explode(';', $balance[0]);

				if (count($balance)) {

					$balance = $balance[1];
				} else {

					$balance = null;
				}

			} else {
				$balance = null;
			}

			return $balance;
		}

		public static function senders() {

			if (!static::isInit()) {
				return null;
			}

			$senders = static::sendHttp('senders');

			return $senders;
		}

		private static function cleanPhone($phone) {

			return '+'.preg_replace('~[^0-9]+~', '', $phone);
		}

		private static function isInit() {

			global $application;

			$configuration = $application->configuration;

			if (isset($configuration['sms'])) {

				static::init($configuration['sms']);
			}

			return count(static::$configuration) && isset($configuration['sms']) && isset($configuration['sms']['production']);
		}

		private static function sendHttp($url, $args = []) {

			$args['login'] = static::$configuration['login'];

			$args['password'] = static::$configuration['password'];

			if (isset(static::$configuration['sender'])) {

				$args['sender'] = static::$configuration['sender'];
			}

			if (count($args)) {
				$args = '?' . http_build_query($args);
			} else {
				$args = '';
			}

			$host = static::$configuration['host'];

			$curl = \curl_init();
			\curl_setopt($curl, CURLOPT_URL, $host. $url . $args);
			\curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$response = \curl_exec($curl);

			\curl_close($curl);

			if ($response) {
				$response = explode("\n", $response);
			} else {
				$response = [];
			}

			return $response;
		}

	}
}