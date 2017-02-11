<?php

function userid() {
	$data = &\Security\Security::$context->data;
	$user = isset($data['user']) ? $data['user'] : null;

	if ($user) {
		return $user['id'];
	}

	return null;
}

function user() {
	$data = &\Security\Security::$context->data;

	return isset($data['user']) ? $data['user'] : null;
}

function isRoot() {
	$data = &\Security\Security::$context->data;

	return isset($data['role']['name']) ? $data['role']['name'] == 'root' : null;
}

function verifyAuthentication() {
	if (!user()) {
		throw new \System\Exception('Authentication required', \System\Code::AuthenticationError);
	}
}

function check($key, $mode = \Security\AccessMode::Execute) {
	return \Security\Security::check($key, $mode);
}

function verify($key, $mode = \Security\AccessMode::Execute) {
	return \Security\Service::verify($key, $mode);
}

function contextData() {
	return \Security\Service::$context->data;
}

function isLoginned() {

	if (!context('user')) {
		echo \JSON::stringify(['401' => 'unavtorized']);
		exit();
	}
}

function context($value = null, $context = null) {

	if (!$context) {
		$context = \Security\Service::$context;
	}

	if (!$context) {
		return $context;
	}

	if ($value) {
		$context = $context->data;

		$values = explode('.', $value);

		foreach ($values as $value) {

			if (!isset($context[$value])) {
				$context = null;
				break;
			}

			$context = $context[$value];
		}
	}

	return $context;
}

function signIn($login, $password) {
	if (!\Security\Security::signIn('Security\Auth\Native', ['login' => $login, 'password' => $password])) {
		throw new Exception('Authentication failed');
	}
}

function signOut() {
	\Security\Security::signOut();
}
