<?php

function appLoad($file) {
	global $APP_PATH;

	$filePath = sprintf('%s/application/%s.php', $APP_PATH, $file);

	if (is_file($filePath)) {
		include_once $filePath;

		return true;
	}

	return false;
}

function appAutoLoad($className) {
	appLoad(str_replace('\\', '/', $className));
}

function appConfiguration($name, $dir = '/etc/glonass') {
	$path = "$dir/$name.json";
	//$config = false;

	//if (is_file($path) && is_readable($path)) {
		$config = @json_decode(file_get_contents($path), true);
	//}

	return $config;
}

function app($name, $options = []) {
	global $application;
	global $CONFIG_DIR;

	$dir = '/etc/glonass';

	if (isset($options['configDir'])) {
		$dir = $options['configDir'];
	}

	if (isset($CONFIG_DIR)) {
		$dir = $CONFIG_DIR;
	}


	if ($configuration = appConfiguration($name, $dir)) {
		$appClass = @$configuration['class'];

		//if (!@class_exists($appClass) || !is_a($appClass, 'Application', true)) {
		//	throw new Exception('Application class not found.');
		//}

		$console = false;

		$appOptions = [];

		if (in_array('console', $options)) {
			$appOptions[] = 'console';
			$console = true;
		}

		/**
		 * @var Application $application
		 */
		$application = new $appClass($configuration, $appOptions);

		$application->init();

		$application->run();

		if (!$console) {
			$application->deinit();
		}

	} else {
		throw new Exception('Failed to read configuration file.');
	}
}

spl_autoload_register('appAutoLoad');
