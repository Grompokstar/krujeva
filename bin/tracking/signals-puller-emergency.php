<?php

include __DIR__ . '/../opts.php';

$opts = optsRead(optsApp([
	[
		'name' => 'interval',
		'short' => 'i',
		'long' => 'interval',
		'description' => 'Интервал между запросами.'
	]
], false), [
	'helpText' => 'signals-puller-emergency.php [Параметры]'
]);

$interval = (int)coalesce($opts['interval'], 2);
$map = [];
$datetime = [];

$connection = \Trackers\Data\Record::$connection;

$foreignParams = [
	'host=10.11.69.12 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.13 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.14 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.15 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.16 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.17 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.18 port=5432 dbname=shard1 user=glonass password=glonass',
	'host=10.11.69.19 port=5432 dbname=shard1 user=glonass password=glonass'
];

$foreignConnection = new \Data\ConnectionGroup($foreignParams);

echo "initialization...\n";

foreach ($foreignParams as $index => $param) {
	$datetime[$index] = $foreignConnection->queryScalar('select max(createddatetime) from trackers.last', null, ['server' => $index]);
}

$trackers = \Trackers\Data\Trackers::select([
	'fields' => 'trackers.*, types.name as typename',
	'join' => [
		[
			'table' => 'trackers.types',
			'on' => 'types.id = trackers.typeid'
		]
	]
]);

foreach ($trackers as $tracker) {
	if ($foreign = $foreignConnection->queryRow('select trackers.id as id, trackers.num as num, types.name as type from trackers.trackers inner join trackers.types on types.id = trackers.typeid where trackers.num = $1 and types.name = $2', [$tracker['num'], $tracker['typename']], ['server' => 0])) {
		$map[$foreign['id']] = [
			'id' => $tracker['id'],
			'server' => $tracker['server']
		];
	}
}

$trackers = implode(',', array_keys($map));

if (!$trackers) {
	exit("no tracker was found\n");
}

echo "initialized.\n";

while (true) {
	foreach ($datetime as $index => &$dt) {
		if ($rows = $foreignConnection->queryRows("select * from trackers.log where datetime > '2014-08-01' and createddatetime > '$dt' and trackerid in ($trackers)", null, ['server' => $index])) {
			foreach ($rows as $row) {
				$id = $map[$row['trackerid']]['id'];
				$server = $map[$row['trackerid']]['server'];

				try {
					$connection->queryScalar('select trackers.loginsert($1, $2, $3, $4, $5, $6, $7, $8, $9)', [$id, $row['datetime'], $row['lon'], $row['lat'], $row['speed'], $row['odometer'], $row['course'], $row['satsglonass'], $row['satsgps']], ['server' => $server]);
				} catch (\System\Exception $e) {
					echo $e->getMessage() . "\n";
				}
			}

			$max = max(array_column($rows, 'createddatetime'));

			if ($max > $dt) {
				$dt = $max;
			}
		}

		$count = count($rows);

		echo "$count rows processed from server $index\n";
	}

	sleep($interval);
}

$foreignConnection->close();

$application->deinit();
