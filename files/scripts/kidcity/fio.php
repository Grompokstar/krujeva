<?php

global $APP_PATH;
$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'kidcity';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');


app('kidcity', ['configDir' => $APP_PATH . '/configuration', 'console']);

function sendHttp($char, $token) {

	$fields = '{"query":"'. $char .'","count":99}';

	$curl = curl_init('https://dadata.ru/api/v2/suggest/fio');

	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json',
		'Authorization: Token '. $token,
	));

	$response = curl_exec($curl);

	curl_close($curl);

	return $response;
}

global $abc;
$abc = ['а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'ы', 'ю', 'я'];

global $tokens;

$tokens = [
	/*'becba90225b52ca89170f47a025df28e16705327',
	'8b3047cd3ec3e584dfa8e726e0a8b6d0cf8d0d9d',
	'67889aed012c31002a18d63109a4a1911931133d',


	'897b15523a53f894cba79b18a63fb37314724e5c',
	'e06e476aca93ef832e837e7a746060df252df5e7',
	'34bb1cfc6cd81273ec73590ee81274e832b5517d',
	'd90ede6b284e6a5728a64ea15957ecc9678da2c8',
	'495434e36c7e0c3287b039bfefe23c66cf4c4e7a',
	'f2bf6ff9320c2e04e38cd8a91255377e4bc434b4',

	'8f294a5f694bd3a7bc890516a4d57ac71e974d16',
	'03b85732d5611aff38821d2a938a354560c5fb6b',
	'89071796500022a4936c50532f8ce2cddb32e866',
	'9b1fceed4530754f68f98da79d838a00b5c552f3',
	'f0d98660ad3d303c68bbe2060106d13a3843448f',
	'55bbe6070a18eae195b579407e08d642bd1ce3a4',
	'07e25cc8e558734c3c60a1eda7cc1926b20c31f6',*/
	'1751fd55ace5fbdf3b9e20bc2f374a698e792dd5', //timeweb
	'50e971f87de6d79105ed8ad6452f5a918d33286c',

];

global $tokenIndex;
$tokenIndex = 0;

function ABC($index, $abc, $count = null) {

	$result = "";

	if (!isset($abc[$index])) {
		$index = $index - count($abc);
		$count++;
		return ABC($index, $abc, $count);
	}

	if ($count != null) {

		if (!isset($abc[($count - 1)])) {

			$char = ABC($count - 1, $abc);

		} else {

			$char = $abc[($count - 1)];
		}

		$result = $char . $abc[$index];

	} else {
		$result = $abc[$index];
	}

	return $result;
}


$limit = 9;
$char = '';
$index = 34341;

function initNames($start, $end) {

	global $abc;
	global $tokens;
	global $tokenIndex;

	for ($i = $start; $i <= $end; $i++) {
		$char = ABC($i, $abc);

		echo date('Y-m-d H:i:s') . ' - ' . $tokenIndex . ' - ' . $i . ' - ' . $char . "\n";

		if (!isset($tokens[$tokenIndex])) {
			var_dump('end tokens');
			exit();
		}

		$token = $tokens[$tokenIndex];

		if (!request($char, $token)) {
			$tokenIndex++;
		}
	}
}




function request($char, $token) {

		$result = true;

		//@request
		$resInitial = sendHttp($char, $token);

		$res = (\JSON::parse($resInitial));

		if ($res && count($res) && isset($res['suggestions']) && is_array($res['suggestions'])) {

			foreach ($res['suggestions'] as $item) {

				$name = $item['value'];

				if ($item['data']['surname']) {

					$old = \City\Data\Surnames::firstBy(['name' => $name]);

					if (!$old) {

						\City\Data\Surnames::insert([
							'name' => $name,
							'sex' => $item['data']['gender'] == 'MALE' ? 1 : 2
						]);
					}

				} else if ($item['data']['name']) {

					$old = \City\Data\Names::firstBy(['name' => $name]);

					if (!$old) {

						\City\Data\Names::insert(['name' => $name]);
					}

				} else if ($item['data']['patronymic']) {

						$old = \City\Data\Patronymics::firstBy(['name' => $name]);

						if (!$old) {

							\City\Data\Patronymics::insert(['name' => $name]);
						}
					}
				}

		} else {

			$result = false;
		}


	return $result;

}
