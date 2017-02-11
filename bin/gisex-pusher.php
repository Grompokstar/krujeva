#!/usr/bin/php
<?php

declare(ticks = 1);

function signalHandler($signo) {
	switch ($signo) {
		case SIGTERM:
		case SIGHUP:
		case SIGINT:
			echo "\nFinishing... ";
			Listener::$active = false;
			break;
		default:
			echo "Unhandled signal $signo\n";
			break;
	}
}

pcntl_signal(SIGTERM, "signalHandler");
pcntl_signal(SIGHUP, "signalHandler");
pcntl_signal(SIGINT, "signalHandler");

class Client {
	public static $host;

	public static function send($data) {
		static::sendMessage(static::url($data['type']), $data['message']);

		return true;
	}

	private static function sendMessage($url, $message) {
		global $config;

		$message = ['message' => json_encode($message)];
		$message['AUTH[login]'] = $config['gisex']['login'];
		$message['AUTH[password]'] = $config['gisex']['password'];

		$response = static::httpRequest($url, $message);

		return $response;
	}

	private static function url($type) {
		return static::$host . '/GISEX/Web/' . $type;
	}

	private static function httpRequest($url, $data) {
		$curl = \curl_init($url);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'X-REQUESTED-WITH: XMLHttpRequest'
		]);

		ob_start();

		$success = \curl_exec($curl);
		\curl_close($curl);

		$response = ob_get_contents();
		ob_end_clean();

		if (!$success) {
			echo "\nURL: $url\nMessage: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

			throw new Exception('Request failed');
		}

		$decoded = json_decode($response, true);

		if (json_last_error() != JSON_ERROR_NONE) {
			echo "\nResponse: $response\n";

			throw new Exception('Failed to decode response');
		}

		$response = $decoded;

		if (!$response) {
			throw new Exception('Invalid response');
		}

		if (!$response['success']) {
			echo "\nURL: $url\nMessage: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) .
				"\nResponse: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

			throw new Exception('Operation failed');
		}

		return $response;
	}
}

class Listener {
	public static $path;
	public static $active = false;
	private static $inotify = null;
	private static $watch = null;

	public static function init() {
		static::$inotify = inotify_init();

		if (!static::$inotify) {
			throw new Exception('inotify_init() failed');
		}

		static::$watch = inotify_add_watch(static::$inotify, static::$path, IN_CLOSE_WRITE);

		static::scan();

		static::$active = true;
	}

	public static function deinit() {
		inotify_rm_watch(static::$inotify, static::$watch);

		fclose(static::$inotify);
	}

	public static function scan() {
		$fileNames = [];

		if ($dir = opendir(static::$path)) {
			while ($fileName = readdir($dir)) {
				if (in_array($fileName, ['.', '..'])) {
					continue;
				}

				$filePath = static::$path . "/$fileName";

				if (!is_file($filePath)) {
					echo "$fileName skip...\n";
					continue;
				}

				if (!is_readable($filePath)) {
					echo "$fileName is not readable, skip...\n";
					continue;
				}

				$fileNames[] = $fileName;
			}

			closedir($dir);
		} else {
			throw new Exception('Failed to open ' . static::$path);
		}

		sort($fileNames);

		foreach ($fileNames as $fileName) {
			static::process($fileName);
		}

		return $fileNames;
	}

	public static function run() {
		while (static::$active) {
			echo "waiting...\n";
			$events = @inotify_read(static::$inotify);

			if (!static::$active || !is_array($events)) {
				break;
			}

			foreach ($events as $event) {
				if ($event['name']) {
					static::process($event['name']);
				}
			}

			while (true) {
				if (!static::scan()) {
					break;
				}
			}
		}
	}

	private static function process($fileName) {
		echo "$fileName\n";

		$filePath = static::$path . '/' . $fileName;

		if (!file_exists($filePath)) {
			echo "$filePath does not exist. Already processed?\n";
			return;
		}

		if (!is_file($filePath) || !is_readable($filePath)) {
			throw new Exception("$filePath is not readable");
		}

		$json = file_get_contents($filePath);
		$data = json_decode($json, true);

		if (json_last_error() != JSON_ERROR_NONE) {
			$error = json_last_error_msg();

			throw new Exception("Failed to decode: $error");
		} else {
			if (Client::send($data)) {
				if (!unlink($filePath)) {
					throw new Exception("Failed to remove message $filePath");
				}
			} else {
				throw new Exception("Failed to send $filePath");
			}
		}
	}
}

function showHelp($message = '') {
	if ($message) $message = "\n$message\n";

	$help = <<<SQL
$message
Usage: gisex-agent <OPTIONS>

    --path      Путь, по которому прослушиваются новые сообщения (обязательно)
    --host      Адрес GISEX-сервера (обязательно)
    --config    Путь к файлу с учётными данными


SQL;

	echo $help;
	exit;
}

$longopts = [
	'path:',
	'host:',
	'config:',
	'help'
];

$opts = getopt('', $longopts);

if (isset($opts['help'])) {
	showHelp();
}

if (!@$opts['host']) {
	showHelp("--host не указан");
}

if (!@$opts['path']) {
	showHelp("--path не указан");
}

if (!@$opts['config']) {
	showHelp("--config не указан");
}

Listener::$path = realpath($opts['path']);
Client::$host = preg_replace('/\/*$/', '', $opts['host']);

if (!Listener::$path) {
	throw new Exception("Invalid path");
}

if (!is_file($opts['config']) || !is_readable($opts['config'])) {
	showHelp("Файл конфигурации не читается");
}

$config = @json_decode(file_get_contents($opts['config']), true);

if (!$config || !isset($config['gisex']) || !isset($config['gisex']['login']) || !isset($config['gisex']['password'])) {
	showHelp("Неверный формат файла конфигурации");
}

$path = Listener::$path;
$host = Client::$host;

$stat = <<<SQL

Path:       $path
Host:       $host


SQL;

echo $stat;

Listener::init();
Listener::run();

echo "finished\n";
