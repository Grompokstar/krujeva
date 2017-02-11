<?php

namespace Krujeva {

	class Context extends \Security\Context {

		public function init() {
			parent::init();
		}

		public function setUser($user, $sessionId = null) {
			parent::setUser($user, $sessionId);
		}

		public static function serverConfig() {

			global $application;

			$configuration = $application->configuration;

			$data['context'] = \Security\Service::$context->data;

			unset($data['context']['sessionId']);

			$timezone = 0;

			$data['config'] =  [
				'message' => ['url' => $configuration['message']['clientURL']],
				'timestamp' => time(),
				'timezone' => $timezone
			];

			return $data;
		}
	}
}
