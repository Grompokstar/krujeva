<?php

function is_using($object, $trait) {
	return in_array($trait, class_uses($object));
}

function coalesce() {
	$args = func_get_args();

	foreach ($args as $arg) {
		if ($arg !== null) {
			return $arg;
		}
	}

	return null;
}

function file_ext($filename) {
	$info = pathinfo($filename);

	return isset($info['extension']) ? $info['extension'] : null;
}

function file_mime_type($filename) {
	if (!$fileinfo = @finfo_open(FILEINFO_MIME_TYPE)) {
		return null;
	}

	$mime = @finfo_file($fileinfo, $filename);
	finfo_close($fileinfo);

	return $mime ? $mime : null;
}

function option($path, $options, $default = null) {
	if (!is_array($path)) {
		$path = [$path];
	}

	while ($path) {
		$key = array_shift($path);

		if (!is_array($options) || !isset($options[$key])) {
			return $default;
		}

		$options = $options[$key];
	}

	return $options;
}

function readPassword($prompt = null) {
	$prompt = coalesce($prompt, "Пароль:") . " ";

	$command = "/usr/bin/env bash -c 'read -s -p \"" . addslashes($prompt) . "\" mypassword && echo \$mypassword'";
	$password = rtrim(shell_exec($command));
	echo "\n";

	return $password;
}
