<?php

class Message {
	public static $mode = null;	// http, redis
	public static $url = null;

	private static $txlevel = 0;
	private static $queue = [];

	protected static $redisChannel = 'ASYNC.MESSAGE';

	public static function init($options = []) {
		static::$mode = option('mode', $options, 'http');
		static::$url = option('url', $options);
		static::$redisChannel = option('channel', $options, 'ASYNC.MESSAGE');
	}

	public static function options() {
		return [
			'mode' => static::$mode,
			'url' => static::$url,
			'redisChannel' => static::$redisChannel
		];
	}

	public static function begin() {
		self::$txlevel++;
	}

	public static function commit() {
		if (self::$txlevel) {
			self::$txlevel--;

			if (!self::$txlevel) {
				self::sendQueue();
			}
		}
	}

	public static function abort() {
		self::$txlevel = 0;
		self::$queue = [];
	}

	public static function event($name, $data, $callback = null) {
		if (self::$txlevel) {
			self::$queue[] = [
				'name' => $name,
				'data' => $data,
				'callback' => $callback
			];

			return true;
		} else {
			return self::send(['event' => $name, 'data' => $data], $callback);
		}
	}

	public static function sendQueue() {
		while ($item = array_shift(self::$queue)) {
			self::event($item['name'], $item['data'], $item['callback']);
		}
	}

	public static function clients() {
		$index = \System\NumSeq::next('clients');

		$url = str_replace('/message', '/clients', self::$url);

		$fields = implode('&', ['index=' . $index]);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);

		curl_close($curl);

		$clients = json_decode($response, true);

		return $clients ? $clients : [];
	}

	public static function send($data, $callback = null) {
		switch (static::$mode) {
			case 'http':
				return static::sendHttp($data, $callback);
				break;
			case 'redis':
				return static::sendRedis($data, $callback);
				break;
		}

		return null;
	}

	private static function sendHttp($data, $callback) {
		$index = \System\NumSeq::next('message');

		$data = JSON::stringify($data);

		$fields = ['index=' . $index . '&data=' . urlencode($data)];

		if ($callback) {
			$fields[] = 'callback=' . urlencode($callback);
		}

		$fields = implode('&', $fields);

		$curl = curl_init(self::$url);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

	private static function sendRedis($data, $callback) {
		$index = \System\NumSeq::next('message');

		$message = [
			'index' => $index,
			'data' => $data,
			'callback' => $callback
		];

		return \Data\SessionRedis::publish(static::$redisChannel, JSON::stringify($message));
	}
}
