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

$foreignConnection = new \Data\Connection('host=188.40.75.212 port=5432 dbname=kidcity user=postgres password=postgres');
$foreignConnection->connect();

echo "query \n";

$items = $foreignConnection->query("select * from kidcity.countries");

$count = $items->size();
echo "Importing $count items\n";

$index = 0;

queryRows('truncate kidcity.countries cascade');

while ($row = $items->row()) {
	$subscriber = \City\Data\Countries::insert($row, ['asis', 'raw']);

	$index++;
	echo "processed record $index\n";
}

echo "query \n";

$items = $foreignConnection->query("select * from kidcity.cities");

$count = $items->size();
echo "Importing $count items\n";

$index = 0;

queryRows('truncate kidcity.cities cascade');

while ($row = $items->row()) {
	$subscriber = \City\Data\Cities::insert($row, ['asis', 'raw']);

	$index++;
	echo "processed record $index\n";
}