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
 * @param $parent
 * @param $parentId
 */
function import($connection, $parent = null, $parentId = null) {
	$query = 'select * from emergency.organizations where department = 4';
	$args = [];

	if ($parentId === null) {
		$query .= ' and parentid is null';
	} else {
		$query .= ' and parentid = $1';
		$args[] = $parentId;
	}

	$query = $connection->query($query, $args);

	while ($row = $query->row()) {
		if ($organization = build($row)) {
			$organization['parentid'] = $parent['id'];
			$organization = \Emergency\Data\Organizations::insert($organization);
			echo "imported {$organization['alias']}\n";

			import($connection, $organization, $row['id']);
		}
	}
}


function build($organization) {
	$item = \Data\Item::translate($organization, [
		'name' => 'name',
		'alias' => 'alias',
		'addresscode' => 'addresscode',
		'addresstext' => 'addresstext',
		'addresshouse' => 'addresshouse',
		'department' => 'department'
	]);

	$item['phonefixed'] = $organization['phonefixed'] ? explode(',', $organization['phonefixed']) : null;
	$item['phonesip'] = $organization['phoneavaya'] ? explode(',', $organization['phoneavaya']) : null;

	$item['gisexid'] = \GISEX\Sender::id();
	$item['gisexgroup'] = [\GISEX\Client::$system];

	return $item;
}

txbegin();

import($connection);

if ($opts['check']) {
	txabort();
	echo "rolled back\n";
} else {
	txcommit();
	echo "committed\n";
}

$connection->close();
