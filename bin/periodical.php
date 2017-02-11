<?php

include __DIR__ . '/opts.php';

$opts = optsRead([
	[
		'name' => 'app',
		'long' => 'app',
		'description' => 'Приложение, для которого запускается демон',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'class',
		'short' => 'c',
		'long' => 'class',
		'description' => 'Название PHP класса с методом run()',
		'options' => OPT_REQUIRED | OPT_ARG_REQUIRED
	],
	[
		'name' => 'interval',
		'short' => 'i',
		'long' => 'interval',
		'description' => 'Интервал между вызовами в секундах (по-умолчанию 1 сек)',
		'options' => OPT_ARG_REQUIRED
	]
], [
	'helpText' => 'periodical.php [Параметры]'
]);

$APP_NAME = $opts['app'];

include __DIR__ . '/daemon.php';

\System\Daemon::init();

$class = $opts['class'];

if (!class_exists($class)) {
	optsHelp([ 'errorText' => "Класс $class не найден." ]);
}

/**
 * @var System\Periodical $object
 */
$object = new $class();

if (!method_exists($object, 'run')) {
	optsHelp([ 'errorText' => 'Отсутствует метод run().' ]);
}

$interval = $opts['interval'] ? (int)$opts['interval'] : 1;

$stat = <<<TEXT
class:    $class
interval: $interval

TEXT;

echo $stat;

\System\Daemon::run(function () use ($object, $interval) {
	$object->run();

	sleep($interval);
});
