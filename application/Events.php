<?php

trait Events {
	protected $subscribers = [];

	public function on($event, $method, $object = null) {
		if (!isset($this->subscribers[$event])) {
			$this->subscribers[$event] = [];
		}

		$this->subscribers[$event][] = [
			'object' => $object,
			'method' => $method
		];
	}

	public function off($event, $method, $object = null) {
		if (!isset($this->subscribers[$event])) {
			return;
		}

		for ($i = 0, $count = count($this->subscribers[$event]); $i < $count; $i++) {
			$item = $this->subscribers[$event][$i];

			if ($item['object'] == $object && $item['method'] == $method) {
				array_splice($this->subscribers[$event], $i);
				return;
			}
		}
	}

	public function emit($event, $args = null) {
		if (!isset($this->subscribers[$event])) {
			return;
		}

		$subscribers = $this->subscribers[$event];

		for ($i = 0, $count = count($subscribers); $i < $count; $i++) {
			$item = $subscribers[$i];

			$object = $item['object'];
			$method = $item['method'];

			if ($object) {
				$object->$method($args);
			} else if (is_callable($method)) {
				$method($args);
			}
		}
	}

	protected static $eventSubscribers = [];

	public static function onEvent($event, $method, $object = null) {
		if (!isset(static::$eventSubscribers[$event])) {
			static::$eventSubscribers[$event] = [];
		}

		static::$eventSubscribers[$event][] = [
			'object' => $object,
			'method' => $method
		];
	}

	public static function offEvent($event, $method, $object = null) {
		if (!isset(static::$eventSubscribers[$event])) {
			return;
		}

		for ($i = 0, $count = count(static::$eventSubscribers[$event]); $i < $count; $i++) {
			$item = static::$eventSubscribers[$event][$i];

			if ($item['object'] == $object && $item['method'] == $method) {
				array_splice(static::$eventSubscribers[$event], $i);
				return;
			}
		}
	}

	public static function emitEvent($event, $args = null) {
		if (!isset(static::$eventSubscribers[$event])) {
			return;
		}

		$eventSubscribers = static::$eventSubscribers[$event];

		for ($i = 0, $count = count($eventSubscribers); $i < $count; $i++) {
			$item = $eventSubscribers[$i];

			$object = $item['object'];
			$method = $item['method'];

			if ($object) {
				$object->$method($args);
			} else if (is_callable($method)) {
				$method($args);
			}
		}
	}
}
 