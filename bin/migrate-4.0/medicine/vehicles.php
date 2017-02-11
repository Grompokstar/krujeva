<?php

include __DIR__ . '/../../opts.php';

$opts = optsRead(optsApp([
	[
		'name' => 'check',
		'long' => 'check',
		'description' => 'Проверить загрузку. Откатить транзакцию по окончании.'
	]
]), [
	'helpText' => 'vehicles.php [Параметры]'
]);

$connection = new \Data\Connection('host=10.11.69.12 dbname=shard1 user=glonass password=glonass');

$query = <<<SQL
select
		vehicles.*,
		organizations.alias as organizationalias,
		vehiclemodels.fullname as modelname,
		trackers.num as trackernum,
		types.name as typename
	from emergency.vehicles
	inner join emergency.organizations on organizations.id = vehicles.organizationid
	left join dict.vehiclemodels on vehiclemodels.id = vehicles.modelid
	left join trackers.trackers on trackers.id = vehicles.trackerid
	left join trackers.types on types.id = trackers.typeid
		where
			organizations.department = 1
SQL;

$query = $connection->query($query);

function getOrganizationId($alias) {
	if (!$organization = \Medicine\Data\Organizations::firstBy(['alias' => $alias])) {
		throw new Exception("Organization $alias not found");
	}

	return $organization['id'];
}

function getModelId($name) {
	if (!$name) {
		return null;
	}

	if (!$model = \Dict\Data\VehicleModels::firstBy(['name' => $name])) {
		$model = \Dict\Data\VehicleModels::insert(['name' => $name]);
		echo "Model {$model['name']} created\n";
	}

	return $model['id'];
}

function getTrackerId($num, $typeName) {
	if (!$num) {
		return null;
	}

	if (!$type = \Trackers\Data\Types::firstBy(['name' => $typeName])) {
		$type = \Trackers\Data\Types::insert(['name' => $typeName]);
	}

	if (!$tracker = \Trackers\Data\Trackers::firstBy(['num' => $num, 'typeid' => $type['id']])) {
		return null;
	}

	return $tracker['id'];
}

txbegin();

while ($row = $query->row()) {
	$vehicle = $row;

	try {
		$vehicle['organizationid'] = getOrganizationId(trim($vehicle['organizationalias']));
		$vehicle['modelid'] = getModelId($vehicle['modelname']);
		$vehicle['trackerid'] = getTrackerId($vehicle['trackernum'], $vehicle['typename']);

		$vehicle = \Medicine\Data\Vehicles::insert($vehicle);

		echo "Vehicle {$vehicle['num']} imported\n";
	} catch (Exception $e) {
		$message = $e->getMessage();

		echo "Vehicle {$vehicle['num']} skipped ($message)\n";
	}
}

if ($opts['check']) {
	txabort();
	echo "rolled back\n";
} else {
	txcommit();
	echo "committed\n";
}
