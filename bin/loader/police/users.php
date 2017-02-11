<?php

include __DIR__ . '/../../opts.php';

$opts = optsRead(optsApp([
	[
		'name' => 'file',
		'short' => 'f',
		'description' => 'Загружаемый файл',
		'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
	],
	[
		'name' => 'check',
		'long' => 'check',
		'description' => 'Проверить загрузку. Откатить транзакцию по окончании.'
	]
]), [
	'helpText' => 'users.php [Параметры]'
]);

$fields = [
	'login' => 'Логин',
	'password' => 'Пароль',
	'rolename' => 'Роль',
	'lastname' => 'Фамилия',
	'firstname' => 'Имя',
	'middlename' => 'Отчество',
	'organizationalias' => 'Организация',
	'positionname' => 'Должность',
	'profilename' => 'Профиль',
	'departmentname' => 'Ведомство'
];

$items = [];

function read($filePath) {
	global $items;

	$content = file_get_contents($filePath);
	$items = explode("\n", trim($content));

	foreach ($items as &$item) {
		$item = explode(';', $item);

		foreach ($item as &$col) {
			$col = trim($col);
		}
	}

	if (!$items) {
		throw new Exception('No data');
	}
}

function initFields() {
	global $fields, $items;

	$header = array_shift($items);

	foreach ($fields as $name => &$value) {
		$value = mb_strtolower($value);

		foreach ($header as $index => $title) {
			if (mb_strtolower($title) == $value) {
				$value = $index;
				continue 2;
			}
		}

		unset($fields[$name]);
	}
}

function get($item, $field) {
	global $fields;

	return @$item[$fields[$field]];
}

function getRoleId($name) {
	if (!$role = \Security\Data\Roles::firstBy(['name' => $name])) {
		$role = \Security\Data\Roles::insert([
			'name' => $name
		]);

		echo "Роль {$role['name']} создана\n";
	}

	return $role['id'];
}

function getOrganizationId($alias) {
	if ($organization = \Police\Data\Organizations::firstBy(['alias' => $alias])) {
		return $organization['id'];
	}

	throw new Exception("Organization $alias not found");
}

function getProfileId($profileName) {
	if (!$profile = \Police\Data\Profiles::firstBy(['name' => $profileName])) {
		$profile = \Police\Data\Profiles::insert(['name' => $profileName]);

		echo "Профиль {$profile['name']} создан\n";
	}

	return $profile['id'];
}

function run() {
	global $items;

	foreach ($items as $item) {
		$user = [
			'login' => get($item, 'login'),
			'password' => get($item, 'password'),
			'roleid' => getRoleId(get($item, 'rolename'))
		];

		$user = \Security\Data\Users::insert($user);

		$employee = [
			'userid' => $user['id'],
			'lastname' => get($item, 'lastname'),
			'firstname' => get($item, 'firstname'),
			'middlename' => get($item, 'middlename'),
			'organizationid' => getOrganizationId(get($item, 'organizationalias')),
			'profileid' => getProfileId(get($item, 'profilename'))
		];

		$employee = \Police\Data\Employees::insert($employee);

		echo "{$user['login']}\t\t{$employee['fullname']} imported\n";
	}
}

txbegin();

read($opts['file']);
initFields();
run();

if ($opts['check']) {
	txabort();
	echo "rolled back\n";
} else {
	txcommit();
	echo "committed\n";
}
