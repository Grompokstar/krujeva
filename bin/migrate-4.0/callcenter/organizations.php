<?php

include __DIR__ . '/../../opts.php';

$opts = optsRead(optsApp([
	[
		'name' => 'check',
		'long' => 'check',
		'description' => 'Проверить загрузку. Откатить транзакцию по окончании.'
	]
]), [
	'helpText' => 'organizations.php [Параметры]'
]);

$connection = new \Data\Connection('host=10.11.69.12 dbname=shard1 user=glonass password=glonass');

/**
 * @param \Data\Connection $connection
 * @param array|int $organization
 * @return array|null
 * @throws Exception
 */
function import($connection, $organization) {
	if (!is_array($organization)) {
		$query = 'select * from emergency.organizations where id = $1';
		$args = [$organization];

		$query = $connection->query($query, $args);

		if (!$row = $query->row()) {
			throw new Exception("Failed to get organization $organization");
		}

		$organization = $row;
	}

	$parentId = null;

	if ($organization['parentid']) {
		if ($parent = import($connection, $organization['parentid'])) {
			$parentId = $parent['id'];
		}
	}

	$organization = build($organization);
	$organization['parentid'] = $parentId;

	if ($item = \CallCenter\Data\Organizations::firstBy(['alias' => $organization['alias']])) {
		$organization = $item;
	} else {
		$organization = \CallCenter\Data\Organizations::insert($organization);
	}

	return $organization;
}


function build($organization) {
	$item = \Data\Item::translate($organization, [
		'name' => 'name',
		'alias' => 'alias',
		'addresscode' => 'addresscode',
		'addresstext' => 'addresstext',
		'addresshouse' => 'addresshouse',
		'department' => 'department',
		'responsibilities' => 'responsibilities'
	]);

	$item['phonefixed'] = $organization['phonefixed'] ? explode(',', $organization['phonefixed']) : null;
	$item['phonesip'] = $organization['phoneavaya'] ? explode(',', $organization['phoneavaya']) : null;

	$item['gisexid'] = \GISEX\Sender::id();
	$item['gisexgroup'] = ['callcenter'];

	$item['activities'] = bits2array($organization['activity']);

	return $item;
}

function bits2array($bits, $size = null) {
	if ($size === null) {
		$size = (int)ceil(log($bits, 2));
	}

	$val = 1;
	$array = [];

	for ($i = 0; $i < $size; $i++) {
		if ($bits & $val) {
			$array[] = ($i + 1);
		}

		$val = $val << 1;
	}

	return $array;
}

txbegin();

$query = 'select * from emergency.organizations where (activity & 2) <> 0';

$query = $connection->query($query);

while ($row = $query->row()) {
	$organization = import($connection, $row);

	echo "imported {$organization['alias']}\n";
}

if ($opts['check']) {
	txabort();
	echo "rolled back\n";
} else {
	txcommit();
	echo "committed\n";
}

$connection->close();
