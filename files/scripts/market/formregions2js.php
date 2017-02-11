<?php
$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'dev';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('dev', ['configDir' => $APP_PATH . '/configuration', 'console']);


$regions = \Dict\Data\Areas::select([
	'fields' => 'id, name',
	'where' => 'areas.countryid = 213',
]);

$regionids = [];

foreach ($regions as &$region) {
	$region['childrens'] = [];

	$region['name'] = str_replace('обл.', 'область', $region['name']);

	$regionids[] = $region['id'];

	//childids
	$region['childrens'] = \Dict\Data\Cities::select([
		'fields' => "array_to_string(array_agg(distinct (id)), ',')",
		'where' => 'areaid = $1',
		'data' => [$region['id']],
		'result' => 'scalar'
	]);

	$region['childrens'] = explode(',', $region['childrens']);
}

$cities = \Dict\Data\Cities::select([
	'fields' => 'id, name, areaid',
	'where' => 'areaid in ('.implode(',', $regionids).')'
]);

$data = "
var Regions = ".json_encode($regions).";
var Cities = ".json_encode($cities).";
";

file_put_contents('areas.js', $data);