<?php

namespace Web {
	abstract class XHRAction extends Action {
		protected $xhrResponse = ['success' => false, 'data' => null, 'message' => '', 'code' => 0];

		public function sendXHRResponse() {
			header('Content-Type: application/json');
			echo \JSON::stringify(['success' => $this->xhrResponse['success'], 'data' => $this->xhrResponse['data'], 'message' => $this->xhrResponse['message'], 'code' => $this->xhrResponse['code']]);
		}

		public function xhrOk($data = true, $message = '', $code = 0) {
			$this->xhrResponse['success'] = true;
			$this->xhrResponse['data'] = $data;
			$this->xhrResponse['message'] = $message;
			$this->xhrResponse['code'] = $code;
		}

		public function xhrError($message, $code = 0, $data = false) {
			$this->xhrResponse['success'] = false;
			$this->xhrResponse['message'] = $message;
			$this->xhrResponse['code'] = $code;
			$this->xhrResponse['data'] = $data;
		}
	}
}
