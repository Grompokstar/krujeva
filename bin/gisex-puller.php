#!/usr/bin/php
<?php

declare(ticks = 1);

function signalHandler($signo) {
	switch ($signo) {
		case SIGTERM:
		case SIGHUP:
		case SIGINT:
			echo "\nFinishing... ";
			Puller::$active = false;
			break;
		default:
			echo "Unhandled signal $signo\n";
			break;
	}
}

pcntl_signal(SIGTERM, "signalHandler");
pcntl_signal(SIGHUP, "signalHandler");
pcntl_signal(SIGINT, "signalHandler");

class Puller {
	public static $host;
	public static $url;
	public static $path;
	public static $interval = 1;
	public static $login = 'gisex';
	public static $password = 'gisex';

	public static $active = false;

	private static $system;
	private static $message = [];

	private static $messageIndex = 0;

	public static function init($system) {
		static::$system = $system;

		static::$message = [
			'source' => $system
		];

		if (!is_readable(static::$path)) {
			throw new Exception('Path is not readable');
		}

		if (!is_writable(static::$path)) {
			throw new Exception('Path is not writable');
		}

		$filePaths = [];

		if ($dir = opendir(static::$path)) {
			while ($fileName = readdir($dir)) {
				if (in_array($fileName, ['.', '..'])) {
					continue;
				}

				$filePath = static::$path . '/' . $fileName;

				if (!is_file($filePath)) {
					continue;
				}

				$filePaths[] = $filePath;
			}

			closedir($dir);
		} else {
			throw new Exception('Failed to open path');
		}

		sort($filePaths);

		foreach ($filePaths as $filePath) {
			$content = file_get_contents($filePath);

			if ($content === false) {
				throw new Exception('Failed to read file');
			}

			$data = json_decode($content, true);

			static::send($data);

			if (!unlink($filePath)) {
				throw new Exception('Failed to remove file ' . $filePath);
			}
		}

		static::$active = true;
	}

	public static function run() {
		$failed = false;
		$exception = null;

		while (static::$active) {
			$data = static::pull();

			if ($data) {
				$count = count($data);
				$datetime = date('Y-m-d H:i:s');

				echo "[$datetime] Pulled $count messages\n";

				foreach ($data as $item) {
					if (!$failed) {
						try {
							static::send($item);
						} catch (Exception $e) {
							$exception = $e->getMessage();
							$failed = true;
						}
					}

					if ($failed) {
						static::fail($item);
					}
				}

				if ($failed) {
					throw new Exception($exception);
				}
			}

			sleep(static::$interval);
		}
	}

	public static function send($data) {
		global $config;

		$message = ['data' => json_encode($data)];
		$message['AUTH[login]'] = $config['gisex']['ownLogin'];
		$message['AUTH[password]'] = $config['gisex']['ownPassword'];

		$response = static::httpRequest(static::$url, $message);

		return $response;
	}

	public static function fail($data) {
		$fileName = sprintf('%s.%s.%s.json', str_pad(microtime(true), 15, '0'), str_pad(++static::$messageIndex, 7, '0', STR_PAD_LEFT), static::$system);

		file_put_contents(static::$path . '/' . $fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}

	public static function pull() {
		global $config;

		$message = [
			'message' => json_encode(static::$message),
			'AUTH[login]' => $config['gisex']['login'],
			'AUTH[password]' => $config['gisex']['password']
		];

		$response = static::httpRequest(static::$host . '/GISEX/Web/Pull', $message, false);

		if (!$response) {
			return false;
		}

		return $response['data'];
	}

	private static function httpRequest($url, $data, $raiseError = true) {
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

			if ($raiseError) {
				throw new Exception('Request failed');
			} else {
				return false;
			}
		}

		$decoded = json_decode($response, true);

		if (json_last_error() != JSON_ERROR_NONE) {
			echo "\nResponse: $response\n";
			
			if ($raiseError) {
				throw new Exception('Failed to decode response');
			} else {
				return false;
			}
		}

		$response = $decoded;

		if (!$response) {
			if ($raiseError) {
				throw new Exception('Invalid response');
			} else {
				return false;
			}
		}

		if (!$response['success']) {
			echo "\nURL: $url\nMessage: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) .
				"\nResponse: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

			if ($raiseError) {
				throw new Exception('Operation failed');
			} else {
				return false;
			}
		}

		return $response;
	}
}

function showHelp($message = '') {
	if ($message) $message = "\n$message\n";

	$help = <<<SQL
$message
Usage: gisex-puller <OPTIONS>

    --path      Путь, куда попадут непринятые сообщения (обязательно)
    --host      Адрес GISEX-сервера (обязательно)
    --system    GISEX-имя текущей системы (обязательно)
    --url       URL текущей системы, по которому будут передаваться данные (обязательно)
    --interval  Частота опроса GISEX-сервера в секундах
    --config    Путь к файлу с учётными данными
    --help      Справка


SQL;

	echo $help;
	exit;
}

$longopts = [
	'path:',
	'host:',
	'system:',
	'url:',
	'interval:',
	'config:',
	'help'
];

$opts = getopt('', $longopts);

if (isset($opts['help'])) {
	showHelp();
}

if (!@$opts['path']) {
	showHelp("--path не указан");
}

if (!@$opts['host']) {
	showHelp("--host не указан");
}

if (!@$opts['system']) {
	showHelp("--system не указан");
}

if (!@$opts['url']) {
	showHelp("--url не указан");
}

if (!@$opts['config']) {
	showHelp("--config не указан");
}

if (@$opts['interval']) {
	Puller::$interval = (int)$opts['interval'];
}

Puller::$host = preg_replace('/\/*$/', '', $opts['host']);
Puller::$url = preg_replace('/\/*$/', '', $opts['url']);
Puller::$path = realpath($opts['path']);

if (!Puller::$path) {
	showHelp("Неверный путь {$opts['path']}");
}

if (!is_file($opts['config']) || !is_readable($opts['config'])) {
	showHelp("Файл конфигурации не читается");
}

$config = @json_decode(file_get_contents($opts['config']), true);

if (!$config || !isset($config['gisex']) || !isset($config['gisex']['login']) || !isset($config['gisex']['password']) || !isset($config['gisex']['ownLogin']) || !isset($config['gisex']['ownPassword'])) {
	showHelp("Неверный формат файла конфигурации");
}

$path = Puller::$path;
$host = Puller::$host;
$system = $opts['system'];
$url = Puller::$url;
$interval = Puller::$interval;

$stat = <<<SQL

Path:       $path
Host:       $host
System:     $system
URL:        $url
Interval:   $interval sec


SQL;

echo $stat;

date_default_timezone_set('Europe/Moscow');

Puller::init($opts['system']);
Puller::run();

echo "finished\n";
