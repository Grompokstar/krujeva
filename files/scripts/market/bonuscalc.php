<?php
$APP_PATH = realpath(__DIR__ . '/../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

$currentTime = date('Y-m-d H:i:s', time());


$needCalculateRows = \Krujeva\Data\BonusAccruals::select([
	'where' => 'utcaccrualdatetime < $1 and added = 0',
	'data' => [$currentTime]
]);

echo $currentTime . "\n";


foreach ($needCalculateRows as $needCalculateRow) {

	echo $needCalculateRow['utcaccrualdatetime'].' - '. $needCalculateRow['bonus']."\n";

	$profile = \Krujeva\Data\UserProfiles::firstBy(['userid' => $needCalculateRow['clientid']]);

	if (!$profile) {
		continue;
	}

	$bonus = (float)$profile['bonus'] + (float)$needCalculateRow['bonus'];

	$profile = \Krujeva\Data\UserProfiles::update([
		'id' => $profile['id'],
		'bonus' => $bonus
	]);

	\Krujeva\Data\BonusAccruals::update([
		'id' => $needCalculateRow['id'],
		'added' => 1
	]);

	\Krujeva\Context::setBonus($profile['userid'], $profile['bonus']);

	$user = \Security\Data\Users::get($profile['userid']);

	//@sms bonus
	\Krujeva\SMS::hairBonus($user['login'], $needCalculateRow['bonus']);
}


echo 'done';