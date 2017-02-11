<?php

include __DIR__ . '/opts.php';

$opts = optsRead([
	[
		'name' => 'app',
		'long' => 'app',
		'description' => 'Приложение, для которого запускается демон',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	]
]);

$APP_NAME = $opts['app'];

include __DIR__ . '/daemon.php';

\System\Daemon::init();

\System\Daemon::log("started");

\System\Daemon::run(function () use ($APP_PATH, $APP_NAME) {
	if ($id = \Reports\Planner::fetch()) {
		$output = [];

		$cmd = "/usr/bin/php $APP_PATH/bin/report.php --app=$APP_NAME --id=$id";

		\System\Daemon::log($cmd);

		exec($cmd, $output);

		$output = implode("\n", $output);

		\System\Daemon::log($output);
	} else {
		sleep(1);
	}
});
