<?php
$APP_PATH = realpath(__DIR__ . '/../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

$roleData = ['name' => 'root'];

$role = \Security\Data\Roles::firstBy(['name' => $roleData['name']]);

if (!$role) {
	$role = \Security\Data\Roles::insert($roleData);
}

$userData = ['roleid' => $role['id'], 'login' => 'root', 'password' => 'root',];

$user = \Security\Data\Users::firstBy(['login' => $userData['login']]);

if (!$user) {
	$user = \Security\Data\Users::insert($userData);
}

echo "created";