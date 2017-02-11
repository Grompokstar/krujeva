<?php

define('OPT_REQUIRED', 1);
define('OPT_ARG_REQUIRED', 2);
define('OPT_ARG_OPTIONAL', 4);

$OPTS = [];
$OPTS_OPTIONS = [];
$OPTS_HELP = [];
$OPTS_INPUT = null;
$OPTS_AUTO_INIT = false;
$OPTS_AUTO_AUTH = false;

function optsRead($opts, $options = []) {
	global $OPTS;
	global $OPTS_OPTIONS;
	global $OPTS_HELP;
	global $OPTS_AUTO_INIT;
	global $OPTS_AUTO_AUTH;
	global $APP_NAME;

	$OPTS = $opts;
	$OPTS_OPTIONS = $options;

	$result = [];

	$shorts = 'h';
	$longs = ['help'];

	$OPTS_HELP = [];

	foreach ($opts as $opt) {
		$helpItem = [
			'param' => [],
			'arg' => '',
			'description' => ''
		];

		$optOptions = isset($opt['options']) ? $opt['options'] : 0;
		$requiredArg = ($optOptions & OPT_ARG_REQUIRED) ? ':' : '';
		$optionalArg = ($optOptions & OPT_ARG_OPTIONAL) ? '::' : '';

		if ($requiredArg) {
			$helpItem['arg'] = '<ARG>';
		} else if ($optionalArg) {
			$helpItem['arg'] = '[<ARG>]';
		}

		if (isset($opt['short'])) {
			$shorts .= $opt['short'] . $requiredArg . $optionalArg;
			$helpItem['param'][] = "-{$opt['short']}";
		}

		if (isset($opt['long'])) {
			$longs[] = $opt['long'] . $requiredArg . $optionalArg;
			$helpItem['param'][] = "--{$opt['long']}";
		}

		$helpItem['param'] = '    ' . implode(', ', $helpItem['param']);

		$helpItem['description'] = $opt['description'];

		if ($optOptions & OPT_REQUIRED) {
			$helpItem['description'] .= ' (обязательный параметр)';
		}

		$OPTS_HELP[] = $helpItem;
	}

	$OPTS_HELP[] = [
		'param' => '    -h, --help',
		'arg' => '',
		'description' => 'Справка'
	];

	$OPTS_INPUT = getopt($shorts, $longs);

	if (isset($OPTS_INPUT['h']) || isset($OPTS_INPUT['help'])) {
		optsHelp();
	}

	foreach ($opts as $opt) {
		$val = optsReadOpt($opt, $OPTS_INPUT);

		$optOptions = isset($opt['options']) ? $opt['options'] : 0;

		if ($val === null && ($optOptions & OPT_REQUIRED)) {
			$param = [];

			if (isset($opt['short'])) {
				$param[] = "-{$opt['short']}";
			}

			if (isset($opt['long'])) {
				$param[] = "--{$opt['long']}";
			}

			$param = implode(', ', $param);

			optsHelp([ 'errorText' => "Не указан параметр $param" ]);
		}

		$result[$opt['name']] = $val;
	}

	if ($OPTS_AUTO_INIT) {
		$APP_NAME = $result['app'];

		include 'glonass.php';

		if ($OPTS_AUTO_AUTH) {
			$password = readPassword();

			signIn($result['user'], $password);
		}
	}

	return $result;
}

function optsReadOpt($opt, $input) {
	$val = null;

	$optOptions = isset($opt['options']) ? $opt['options'] : 0;
	$requiredArg = $optOptions & OPT_ARG_REQUIRED;
	$optionalArg = $optOptions & OPT_ARG_OPTIONAL;

	if ($requiredArg) {
		if (@isset($input[$opt['short']])) {
			$val = $input[$opt['short']];
		} else if (@isset($input[$opt['long']])) {
			$val = $input[$opt['long']];
		}
	} else if ($optionalArg) {
		if (@isset($input[$opt['short']])) {
			$val = $input[$opt['short']] === false ? true : $input[$opt['short']];
		} else if (@isset($input[$opt['long']])) {
			$val = $input[$opt['long']] === false ? true : $input[$opt['long']];
		}
	} else {
		if (@isset($input[$opt['short']]) || @isset($input[$opt['long']])) {
			$val = true;
		}
	}

	return $val;
}

function optsHelp($options = []) {
	global $OPTS_HELP, $OPTS_OPTIONS;

	$options = array_merge($OPTS_OPTIONS, $options);

	$maxParamLen = 0;
	$maxArgLen = 0;

	foreach ($OPTS_HELP as $item) {
		$maxParamLen = max($maxParamLen, strlen($item['param']));
		$maxArgLen = max($maxArgLen, strlen($item['arg']));
	}

	if (isset($options['errorText'])) {
		echo "{$options['errorText']}\n\n";
	}

	if (isset($options['helpText'])) {
		echo "{$options['helpText']}\n\n";
	}

	foreach ($OPTS_HELP as $item) {
		echo str_pad($item['param'], $maxParamLen + 4, ' ');
		echo str_pad($item['arg'], $maxArgLen + 4, ' ');
		echo "{$item['description']}\n";
	}

	echo "\n";

	exit;
}

function optsApp($merge = [], $auth = true) {
	global $OPTS_AUTO_INIT;
	global $OPTS_AUTO_AUTH;

	$OPTS_AUTO_INIT = true;
	$OPTS_AUTO_AUTH = $auth;

	if (!is_array($merge)) $merge = [];

	return array_merge([
		[
			'name' => 'app',
			'long' => 'app',
			'description' => 'Приложение',
			'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
		],
		[
			'name' => 'user',
			'short' => 'U',
			'long' => 'user',
			'description' => 'Пользователь',
			'options' => OPT_ARG_REQUIRED | OPT_REQUIRED
		]
	], $merge);
}