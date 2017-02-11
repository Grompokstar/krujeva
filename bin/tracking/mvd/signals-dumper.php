<?php

$PATH = realpath(__DIR__);

include "$PATH/pg.php";

function array_column($array, $column) {
	$result = [];

	foreach ($array as $item) {
		$result[] = @$item[$column];
	}

	return $result;
}

date_default_timezone_set('Europe/Moscow');

$interval = 5;
$datetime = [];
$dump = [];

$dumpFile = '/usr/local/www/emergency/public/signals.json';

$params = [
	'host=10.145.113.23 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.145.113.24 port=5432 dbname=shard1 user=glonass password=glonass'
];

$connection = new splib\data\pg($params);

echo "initialization...\n";

foreach ($params as $index => $param) {
	$datetime[$index] = $connection->queryScalar('select max(createddatetime) from trackers.last', null, $index);
}

echo "initialized.\n";

while (true) {
	$dump['rows'] = [];

	foreach ($datetime as $index => &$dt) {
		$query = <<<SQL
select
		log.*,
		trackers.num as num,
		types.name as type
	from trackers.log
	inner join trackers.trackers on trackers.id = log.trackerid
	inner join trackers.types on types.id = trackers.typeid
		where
			types.name in ('mvd', 'sumn') and
			log.datetime > '2014-08-01' and
			log.createddatetime > '$dt'
SQL;

		if ($rows = $connection->queryRows($query, null, $index)) {
			$dump['rows'] = array_merge($dump['rows'], $rows);

			$max = max(array_column($rows, 'createddatetime'));

			if ($max > $dt) {
				$dt = $max;
			}
		}

		$count = count($rows);

		echo "$count rows processed from server $index\n";
	}

	if ($dump['rows']) {
		$dump['datetime'] = date('Y-m-d H:i:s');

		if (file_put_contents($dumpFile, json_encode($dump, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
			throw new \Exception("Looks like $dumpFile is not a writable path");
		}
	}

	sleep($interval);
}

$connection->close();
