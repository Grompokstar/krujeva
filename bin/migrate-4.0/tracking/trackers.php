<?php

include __DIR__ . '/../../opts.php';

$opts = optsRead(optsApp([
	[
		'name' => 'host',
		'long' => 'host',
		'description' => 'БД хост системы 4.0',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'check',
		'long' => 'check',
		'description' => 'Проверить загрузку. Откатить транзакцию по окончании.'
	]
]), [
	'helpText' => 'trackers.php [Параметры]'
]);

$connection = new \Data\Connection("host={$opts['host']} dbname=shard1 user=glonass password=glonass");

txbegin();

$query = $connection->query('select * from trackers.types');

while ($row = $query->row()) {
	\Trackers\Data\Types::insert($row);
}

$query = $connection->query('select * from trackers.vendors');

while ($row = $query->row()) {
	\Trackers\Data\Vendors::insert($row);
}

$systems = [];

foreach (['police' => Department::Police, 'medicine' => Department::Health, 'emergency' => Department::Emergency] as $name => $department) {
	if (!$system = \Trackers\Data\Systems::firstBy(['name' => $name])) {
		$system = \Trackers\Data\Systems::insert(['name' => $name]);
	}

	$systems[$department] = $system['id'];
}

$query = <<<SQL
select
		trackers.*, organizations.department, types.name as typename, vendors.name as vendorname
	from trackers.trackers
	left join trackers.types on types.id = trackers.typeid
	left join trackers.vendors on vendors.id = trackers.vendorid
	inner join emergency.vehicles on vehicles.trackerid = trackers.id
	inner join emergency.organizations on organizations.id = vehicles.organizationid
		where organizations.department in (1, 2, 4)
SQL;

$query = $connection->query($query);

while ($row = $query->row()) {
	if ($row['typename']) {
		$row['typeid'] = null;

		if ($type = \Trackers\Data\Types::firstBy(['name' => $row['typename']])) {
			$row['typeid'] = $type['id'];
		}
	}

	if ($row['vendorname']) {
		$row['vendorid'] = null;

		if ($vendor = \Trackers\Data\Vendors::firstBy(['name' => $row['vendorname']])) {
			$row['vendorid'] = $vendor['id'];
		}
	}

	$row['server'] = rand(0, 1);

	$row['systems'] = [$systems[$row['department']]];

	$tracker = \Trackers\Data\Trackers::insert($row);

	echo "tracker {$tracker['num']} imported\n";
}

txcommit();

echo "committed\n";
