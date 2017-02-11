<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');
$APP_NAME = 'ts';

require "$APP_PATH/bin/glonass.php";

$roleData = ['name' => 'root'];

$role = \Security\Data\Roles::firstBy(['name' => $roleData['name']]);

if (!$role) {
	$role = \Security\Data\Roles::insert($roleData);
}


$userData = [
	'roleid' => $role['id'],
	'login' => 'admin',
	'password' => 'admin',
];

$user = \Security\Data\Users::firstBy(['login' => $userData['login']]);

if (!$user) {
	$user = \Security\Data\Users::insert($userData);
}



$organizationData = [
	'name' => 'Первая организация'
];

$organization = \Transport\Data\Organizations::firstBy(['name' => $organizationData['name']]);

if (!$organization) {
	$organization = \Transport\Data\Organizations::insert($organizationData);
}


$userProfileData = [
	'userid' => $user['id'],
	'organizationid' => $organization['id']
];

$profile = \Transport\Data\UserProfiles::firstBy(['userid' => $userProfileData['userid']]);

if (!$profile) {
	$profile = \Transport\Data\UserProfiles::insert($userProfileData);
}

echo "created";