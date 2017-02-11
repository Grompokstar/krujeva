<?php

ini_set('display_errors', 'on');
ini_set('error_reporting', '-1');

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

$APP_PATH = realpath(__DIR__);

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('dev', ['configDir' => $APP_PATH . '/configuration']);