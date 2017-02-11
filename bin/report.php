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
		'name' => 'id',
		'long' => 'id',
		'description' => 'Идентификатор отчёта для формирования',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	]
]);

$APP_NAME = $opts['app'];

include __DIR__ . "/glonass.php";

$id = $opts['id'];

if (!$id) {
	echo "id is empty\n";
	exit;
}

\Reports\Planner::exec($id);

echo "finished\n";
