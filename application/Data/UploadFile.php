<?php

namespace Data {

	class UploadFile {
		protected $file;
		protected $errors = [];
		protected $path = '';

		public static $ImageType = [
			'image/png',
			'image/jpeg',
			'image/jpg',
			'image/bmp',
			'image/gif'
		];
		public static $ApkType = ['application/vnd.android.package-archive'];
		public static $ExcelType = [
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.ms-excel',
			'application/msexcel',
			'application/x-msexcel',
			'application/x-ms-excel',
			'application/x-excel',
			'application/x-dos_ms_excel',
			'application/xls',
			'application/x-xls',
		];
		public static $PdfType = ['application/pdf'];
		public static $MovieType = ['video/x-flv', 'video/x-ms-wmv', 'video/mp4', 'video/x-ms-wmv', 'application/octet-stream'];
		public static $AudioType = ['application/octet-stream', 'audio/mpeg', 'audio/mp3'];

		public static function formPath($path) {
			$strlen = mb_strlen($path, 'utf-8');

			if ($path[0] !== '/') {
				$path = '/' . $path;
			}

			if (isset($path[$strlen - 1]) && $path[$strlen - 1] !== '/') {
				$path .= '/';
			}

			return $path;
		}

		public static function getPath($absolute = true) {

			global $APP_PATH;

			global $application;

			$configuration = $application->configuration;

			$path = static::formPath($configuration['upload']['path']) . date('Y/m/d/');

			if ($absolute) {

				$path = $APP_PATH . $path;
			}

			$path = static::formPath($path);

			if ($absolute && !is_dir($path)) {

				@mkdir($path, 0777, true);

				if (!is_dir($path)) {
					return false;
				}
			}

			return $path;
		}

		public static function removeFile($path) {
			if(!is_file($path)){
				return;
			}

			if (!is_writable($path)) {
				return;
			}

			return unlink($path);
		}

		public function __construct($file, $args = []){
			if
			(
				!isset($file['name']) ||
				!isset($file['tmp_name']) ||
				!isset($file['type']) ||
				!isset($file['size'])
			)
			{
				$this->setError('name', 'Undefined name');
				return;
			}

			$this->file = $file;

			$this->file['extension'] = $this->getExtensionName();

			$this->validate($args);
		}

		public function isValid() {

			if (count($this->errors)) {
				return false;
			}

			return true;
		}

		public function save($path = false, $filename = false) {
			if(!$this->isValid()){
				return false;
			}

			if(!$filename) {

				$filename = static::randName($this->file['extension']);

			} else if((strrpos($filename, '.')) === false) {

				$filename .= '.'.$this->file['extension'];
			}

			if(!$path){
				$path = $this->getPath();
				$relative_path = $this->getPath(false);
			}else{
				$relative_path = $path;
			}

			if (!is_dir($path)) {
				@mkdir($path, 0777, true);

				if (!is_dir($path)) {
					$this->setError('path', 'Not valid save path');
					return false;
				}
			}

			if(move_uploaded_file($this->file['tmp_name'], $path . $filename)){
				$this->file['saved_path'] = $path;
				$this->file['relative_path'] = $relative_path;
				$this->file['saved_name'] = $filename;
				$this->file['saved_path_name'] = $path . $filename;
				return true;
			}

			return false;
		}

		public static function randName($image_extension) {
			$arr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
			$rand = (string)md5(time() . rand(1, 5000));
			$name = substr($rand, 1, rand(7, 10));

			return $arr[rand(0, count($arr) - 1)] . $arr[rand(0, count($arr) - 1)] . '_' . $name . '.' . $image_extension;
		}

		private function getExtensionName() {

			if (($pos = strrpos($this->file['name'], '.')) !== false){
				return mb_strtolower(substr($this->file['name'], $pos + 1), 'utf-8');
			}

			return null;
		}

		public static function getExtension($file) {

			if (($pos = strrpos($file, '.')) !== false) {
				return mb_strtolower(substr($file, $pos + 1), 'utf-8');
			}

			return null;
		}

		private function validate($args) {
			$this->validateExtension();
			$this->validateSize(5, true);

			foreach ($args as $name=>$value) {
				switch ($name) {
					case 'size':
						$this->validateSize($value);
						break;

					case 'type':
						$this->validateType($value);
						break;
				}
			}
		}

		private function validateExtension(){

			if(!$this->file['extension']){
				$this->setError('extension', 'Undefined extension');
				return false;
			}

			return true;
		}

		private function validateSize($sile, $isMinSize = false){

			if($isMinSize){

				if($this->file['size'] < (int)$sile){
					$this->setError('size', 'Very small file');
					return false;
				}

				return true;
			}

			if($this->file['size'] > (int)$sile){
				$this->setError('size', 'Very big file');
				return false;
			}

			return true;
		}

		private function validateType($types) {

			if (!in_array(file_mime_type($this->file["tmp_name"]), $types)) {
				$this->setError('type', 'Not valid Type');
				return false;
			}

			return true;
		}

		private function setError($key, $value){
			$this->errors[$key]=$value;
		}

		public function getErrors($glue = null){

			if($glue===null) {
				return $this->errors;
			}

			$errors = [];
			foreach($this->errors as $error){
				$errors[] = $error;
			}

			return implode($glue, $errors);
		}

		public function get($key){
			if(!isset($this->file[$key])){
				return null;
			}

			return $this->file[$key];
		}

	}
}
