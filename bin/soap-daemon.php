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
		'name' => 'count',
		'long' => 'count',
		'description' => 'Максимальное количество дочерних процессов выполняющих вызовы',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'debug',
		'long' => 'debug',
		'description' => 'Режим отладки (true для включения)',
		'options' => OPT_ARG_OPTIONAL
	]
]);

$APP_NAME = $opts['app'];

include __DIR__ . '/daemon.php';

$maxSubProcesses = (int)$opts['count'];
$maxSubProcesses = $maxSubProcesses > 1 ? $maxSubProcesses : 1;

$debug = isset($opts['debug']) && strtolower($opts['debug']) === 'true';

$stat = <<<TEXT
maxSubProcesses: $maxSubProcesses
debug: $debug

TEXT;

echo $stat;

$object = new \SOAP\Daemon($maxSubProcesses, $debug);

\System\Daemon::run(function () use ($object) {
	$object->run();
});
