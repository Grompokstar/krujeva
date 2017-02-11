<?php

namespace Compressor {

	class IncludeStatic {
		protected $path;
		public $config;
		protected $includedJs = [];
		public $version = 0;
		public $isInit = false;

		public function __construct($type) {
			global $application;

			$config = $application->configuration;

			if (!isset($config['static'])) {
				return;
			}

			if (!isset($config['static'][$type])) {
				return;
			}

			global $APP_PATH;

			$this->path = $APP_PATH;
			$this->config = $config['static'];
			$this->version = $this->config['version'];
			$this->isInit = true;
		}

		public function init($type, $name, $files = [], $options = []) {
			$result = false;

			switch($type) {
				case 'css':
					$result = $this->includeCss($name, $files, $options);
					break;
				case 'js':
					$result = $this->includeJsFiles($name, $files, $options);
					break;
			}

			return $result;
		}

		public function getPath($type) {
			return '/public/'.$this->config[$type]['savePath'];
		}

		public function getSavePath($type) {
			return $this->path . '/public/' . $this->config[$type]['savePath'];
		}

		private function getSaveName($type) {
			return $this->config[$type]['name'];
		}

		/*
		 * PRIVATE
		 */
		private function validateSaveDir($type) {
			$path = $this->getSavePath($type);

			if (!is_dir($path)) {
				$res = @mkdir($path, 0777, true);

				if (!$res) {
					return false;
				}
			}

			if (!is_writable($path)) {
				return false;
			}

			return true;
		}

		private function includeCss($name, $files, $options = []) {
			$type = 'css';

			if (!$this->validateSaveDir($type)) {
				return false;
			}

			$data = '';
			foreach ($files as $file) {
				$file = $this->path . $file;

				if (!@file_get_contents($file)) {
					continue;
				}

				$file = $this->includeCssOne($file);

				if(!$file) {
					continue;
				}

				$data .= $file;
			}

			$data = str_replace("\r\n", '', $data);
			$data = str_replace("\n", "", $data);

			$css4 = new Css4();
			$css4->form($data);

			if (isset($options['returnData'])) {
				return $data;
			}

			$putdir = $this->getSavePath($type).$name;

			return @file_put_contents($putdir, $data);
		}

		private function includeCssOne($file) {
			$text = file($file);
			$find = '@import';
			$data = '';
			$path = '';

			if (($pos = strrpos($file, '/')) !== false) {
				$path = substr($file, 0, $pos + 1);
			}

			foreach ($text as $line) {

				if (strpos($line, $find) !== false) {
					$incFile = explode($find, $line);

					if (isset($incFile[1])) {
						$incCss = trim(str_replace(['"',';', "'"], '', $incFile[1]));

						if(!$incCss || !$path) {
							continue;
						}

						$incFile = $this->includeCssOne($path . $incCss);

						if ($incFile) {
							$data .= $incFile . "\n";
						}
						continue;
					}
				}

				$data .= $line;
			}

			return $data;
		}

		private function includeJsFiles($name, $files, $options = []) {
			$type = 'js';

			if (!$this->validateSaveDir($type)) {
				return false;
			}

			$data = '';
			foreach ($files as $file) {

				if (is_array($file)) {
					$file = $this->includeOtherJs($file);
				} else {
					$file = $this->path . '/public/' . $file;
					$file = @file_get_contents($file);
				}

				if (!$file) {
					continue;
				}

				$data .= "\n". $file;
			}

			if (isset($options['returnData'])) {
				return $data;
			}

			$putdir = $this->getSavePath($type) . $name;

			return @file_put_contents($putdir, $data);
		}

		private function includeOtherJs($file) {
			$result = false;

			switch ($file[0]) {
				case 'includeEJS':
					unset($file[0]);

					$result = '';
					foreach($file as $f) {
						$result .= $this->includeEJsFiles($f);
					}
					break;

				case 'includeJS':
					unset($file[0]);

					$result = 'var compressedFiles = true;';
					foreach ($file as $f) {
						$result .= $this->includeJsDir($f);
					}
					$result .= 'compressedFiles = undefined;';

					break;
			}

			return $result;
		}

		private function getListFiles($dir) {
			if (!is_dir($dir)) {
				return [];
			}

			$list = [];

			foreach(scandir($dir) as $file) {
				if (in_array($file, ['.', '..'])) {
					continue;
				}

				$filepath  = $dir . $file;

				if (is_dir($filepath)) {
					$list = array_merge($list, $this->getListFiles($filepath.'/'));
				}else if(is_file($filepath)) {
					$list[] = $filepath;
				}
			}

			return $list;
		}

		private function includeEJsFiles($dir) {
			$files = $this->getListFiles($this->path. $dir);

			$data = '';
			foreach($files as $file) {
				$name = explode($this->path .'/public/', $file);

				if (!isset($name[1])) {
					continue;
				}

				$name = $name[1];

				$file = @file_get_contents($file);

				if (!$file) {
					continue;
				}

				$file = str_replace("\r\n", '', $file);
				$file = str_replace('"\n"', "'\n'", $file);
				$file = str_replace("\n", "", $file);
				$file = str_replace('"', '\"', $file);
				$file = str_replace("'\n'", '"\n"', $file);

				$data.= 'new EJS({text: "'. $file.'", name:"'. $name.'"});'."\n";
			}

			return $data;
		}

		private function includeJsDir($dir) {

			$data = '';

			if (is_file($this->path . $dir)) {

				$data .= $this->includeJs($this->path . $dir, true);

			} else {

				$files = $this->getListFiles($this->path . $dir);

				foreach ($files as $file) {
					$data .= $this->includeJs($file, true);
				}
			}

			return $data;
		}

		private function includeJs($file, $absolutePath = false) {
			if(!$absolutePath) {
				$file = $this->path . '/public/' . $file;
			}

			if ($this->isIncluded($file)) {
				return false;
			}

			$this->includedJs[] = $file;

			if (!@file_get_contents($file)) {
				return false;
			}

			$text = file($file);
			$data =  $this->includeJsLib($text);

			foreach ($text as $line) {
				$data .= $line;
			}

			return $data;
		}

		private function includeJsLib($text) {
			$data = '';

			$isStart = false;
			$isEnd = false;
			$startFind = 'Module.define(';
			$endFind = 'function';

			$included = '';

			foreach ($text as $line) {

				if ($isEnd) {
					break;
				}

				if (!$isStart && strpos($line, $startFind) !== false) {
					$incFile = explode($startFind, $line);

					if (isset($incFile[1])) {

						if (strpos($incFile[1], $endFind) !== false) {
							$incFile = explode($endFind, $incFile[1]);
							$included .= $incFile[0];
							$isEnd = true;
						}else {
							$included .= $incFile[1];
						}

						$isStart = true;
					}
				}else if ($isStart && !$isEnd) {

					if(strpos($line, $endFind) !== false) {
						$incFile = explode($endFind, $line);
						$included .= $incFile[0];
						$isEnd = true;
					}else {
						$included .= $line;
					}
				}
			}

			$included = trim(str_replace(['"', '"'], '', $included));
			$included = explode(',', $included);

			foreach($included as $includejs) {
				if(!$includejs) {
					continue;
				}

				$name = str_replace('.', '/', trim($includejs));
				$file = 'js/' . $name . '.js';

				if ($this->isIncluded($file)) {
					continue;
				}

				$this->includedJs[] = $file;

				$data .= "\n".$this->includeJs($file);
			}

			return $data;
		}

		private function isIncluded($file) {
			return in_array($file, $this->includedJs);
		}

	}
}