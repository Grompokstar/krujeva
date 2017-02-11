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
	'helpText' => 'signals-puller-mvd.php [Параметры]'
]);

$url = 'http://85.233.72.82/signals.json';
$interval = (int)coalesce($opts['interval'], 3);

$connection = \Trackers\Data\Record::$connection;
$packetDatetime = null;

echo "initialized.\n";

while (true) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($curl);
	curl_close($curl);

	$packet = JSON::parse($json);

	if (is_array($packet) && isset($packet['rows']) && is_array($packet['rows']) && isset($packet['datetime']) && $packet['datetime'] != $packetDatetime) {
		$packetDatetime = $packet['datetime'];
		$rows = $packet['rows'];
		$count = count($rows);

		if ($count) {
			foreach ($rows as $row) {
				if ($tracker = $connection->queryRow('select trackers.* from trackers.trackers inner join trackers.types on types.id = trackers.typeid where trackers.num = $1 and types.name = $2', [$row['num'], $row['type']])) {
					try {
						$connection->queryScalar('select trackers.loginsert($1, $2, $3, $4, $5, $6, $7, $8, $9)', [$tracker['id'], $row['datetime'], $row['lon'], $row['lat'], $row['speed'], $row['odometer'], $row['course'], $row['satsglonass'], $row['satsgps']], ['server' => $tracker['server']]);
					} catch (\System\Exception $e) {
						echo $e->getMessage() . "\n";
					}
				}
			}

			echo "$count rows processed\n";
		}
	}

	sleep($interval);
}

$application->deinit();
