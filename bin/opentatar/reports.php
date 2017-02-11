<?php

include __DIR__ . '/../opts.php';

$opts = optsRead([
	[
		'name' => 'app',
		'long' => 'app',
		'description' => 'Приложение, для которого запускается демон',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'type',
		'long' => 'type',
		'description' => 'Тип отчетов daily - для ежедневных отчетов,  monthly - для ежемесячных',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'date',
		'short' => 'date',
		'long' => 'date',
		'description' => 'Дата в формате Y-m-d на которую будет формироваться отчет.',
		'options' => OPT_ARG_OPTIONAL
	]
], [
	'helpText' => 'reports.php [Параметры]'
]);

$APP_NAME = 'callcenter';

include __DIR__ . "/../glonass.php";

$config = $application->configuration['integration']['opentatar'];

if (!$config['enabled']) {
	echo "Disabled (check config file)\n";
	exit;
}

$type = $opts['type'];
if (!in_array($type, ['daily', 'monthly'])) {
	echo "Bad type\n";
	exit;
}

if (isset($opts['date'])) {
	$timestamp = strtotime($opts['date']);

	if (!$timestamp) {
		echo "Bad date\n";
		exit;
	}

	$date = date('Y-m-d', $timestamp);
} else {
	$date = date('Y-m-d');
}

switch ($type) {
	case 'daily':
		(new \CallCenter\Integration\OpenTatar\DailyTasks())->run($date);
		break;
	case 'monthly':
		(new \CallCenter\Integration\OpenTatar\MonthlyTasks())->run($date);
		break;
}
