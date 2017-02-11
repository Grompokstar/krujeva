<?php

global $APP_PATH, $APP_NAME;

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

if (!isset($APP_NAME)) {
	throw new Exception('APP_NAME not set');
}

$APP_PATH = realpath(__DIR__ . '/..');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app($APP_NAME, ['console']);
