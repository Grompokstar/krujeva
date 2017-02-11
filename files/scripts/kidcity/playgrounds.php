<?php


$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'kidcity';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('kidcity', ['configDir' => $APP_PATH . '/configuration', 'console']);


$items = \City\Data\Playgrounds::select();

foreach ($items as $item) {

	$item['fulltimeenteramount'] = 500;
	$item['pathtimeenteramount'] = 250;

	$ft = [
		['amount' => 5000, 'persent' => 5],
		['amount' => 15000, 'persent' => 10],
		['amount' => 25000, 'persent' => 15]
	];

	$item['fulltimepersents'] = JSON::stringify($ft);
	$item['pathtimepersents'] = JSON::stringify($ft);

	\City\Data\Playgrounds::update($item);
}

var_dump($items);
