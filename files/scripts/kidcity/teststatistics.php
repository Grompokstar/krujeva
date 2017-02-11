<?php


$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'kidcityproduction';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('kidcityproduction', ['configDir' => $APP_PATH . '/configuration', 'console']);




$fromdate = '2015-12-09';

$todate = '2015-12-10';

$result = \City\Data\PlaygroundStatistics::statistic([2], $fromdate, $todate, 'hour');

var_dump($result);

