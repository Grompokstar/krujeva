<?php

$APP_PATH = realpath(__DIR__ . '/..');

$APP_NAME = 'dev';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('dev', ['configDir' => $APP_PATH . '/configuration', 'console']);


$luaSrcPath = $APP_PATH.'/lua/src';

$luaBuildPath = $APP_PATH.'/lua/build';

exec('rm -rf '. $luaBuildPath. '/*');


class LuaBuild {

	public $compiledfiles = [];


	public function expandDirectories($base_dir, $moveDirectory, $baseDir) {

		$directories = array();

		foreach (scandir($base_dir) as $file) {

			if ($file == '.' || $file == '..') {
				continue;
			}

			$dir = $base_dir . DIRECTORY_SEPARATOR ;


			if (is_dir($dir . $file)) {

				$this->expandDirectories($dir . $file, $moveDirectory, $baseDir);

			} else {

				if (!@file_get_contents($dir. $file)) {
					continue;
				}

				$this->formFile($dir, $file, $baseDir, $moveDirectory);

			}
		}
		return $directories;
	}

	public function formFile($dir, $file, $baseDir, $moveDirectory) {

		//isset
		if (isset($this->compiledfiles[$dir . $file])) {
			return $this->compiledfiles[$dir . $file];
		}

		$data = '';

		if (!@file_get_contents($dir . $file)) {
			return $data;
		}

		$text = file($dir. $file);

		$find = '@import';

		foreach ($text as $line) {

			if (strpos($line, $find) !== false) {
				$incFile = explode($find, $line);

				if (isset($incFile[1])) {

					$incCss = trim(str_replace(['"', ';', "'"], '', $incFile[1]));

					$nDir = $dir;

					if ($incCss[0] == '/') {
						$dir = $baseDir;
					}

					$filepaths = explode('/', $incCss);

					$incCss = array_pop($filepaths);

					$nDir = $nDir . implode('/', $filepaths);

					$nDir = realpath($nDir);

					$strlen = mb_strlen($nDir, 'utf-8');

					if (isset($nDir[$strlen - 1]) && $nDir[$strlen - 1] !== '/') {
						$nDir .= '/';
					}

					$incFile = $this->formFile($nDir, $incCss, $baseDir, $moveDirectory);

					if ($incFile) {
						$data .= $incFile . "\n";
					}
					continue;
				}
			}

			$data .= $line;
		}

		//@need whrite file
		$needDir = str_replace($baseDir, $moveDirectory, $dir);

		if (!is_dir($needDir)) {
			mkdir($needDir, '0777', true);
		}

		$this->compiledfiles[$dir. $file] = $data;

		var_dump($needDir . $file);

		file_put_contents($needDir.$file, $data);

		return $data;
	}

}

$a = new LuaBuild();
$a->expandDirectories($luaSrcPath, $luaBuildPath, $luaSrcPath);

exec('nginx -s reload');