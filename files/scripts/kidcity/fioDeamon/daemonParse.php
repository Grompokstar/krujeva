#!/usr/bin/php
<?php
//use  = nohup php /usr/local/www/daemonParse/bin/search/daemonParse.php > /tmp/parseSites.log &

date_default_timezone_set('Europe/Moscow');
mb_internal_encoding("UTF-8");

//exec('killall -9 daemonParse.php');

declare(ticks = 1);

class Daemon {
	protected $stop_server = false;

	public $maxProcesses = 30; //максимальное количество процессов
	protected $currentJobs = []; //работа которая сейчас выполняется
	public $currentIndex = 353513;

	public function __construct() {
		pcntl_signal(SIGTERM, array($this, "childSignalHandler")); //завершения работы
		pcntl_signal(SIGCHLD, array($this, "childSignalHandler")); //от дочерних процессов
	}

	public function run() {
		echo "init()\n";

		// Пока $stop_server не установится в TRUE, гоняем бесконечный цикл
		while (!$this->stop_server) {
			sleep(5);
			$this->killSlowProcess();

			for ($i=0; $i<$this->maxProcesses; $i++) {
				if (count($this->currentJobs) >= $this->maxProcesses) {
					continue;
				}

				$this->launchJob();
			}

			// Если уже запущено максимальное количество дочерних процессов, ждем их завершения
			while (count($this->currentJobs) >= $this->maxProcesses) {
				//static::consoleLog("Maximum children allowed, waiting...");
				$this->killSlowProcess();
				sleep(5);
			}
		}

		echo "finish \n";
	}

	protected function launchJob() {
		$pid = pcntl_fork();

		$step = 100;

		// Не удалось создать дочерний процесс
		if ($pid == -1) {
			static::consoleLog('Could not launch new job, exiting');
			return false;
		}

		if ($pid) {
			echo 'init work PID ' . $pid . "\n";

			$this->currentJobs[$pid] = [
				'pid' => $pid,
				'inittime'=>time()
			];

			$this->currentIndex += $step;

			// $this->countJobs();

		} else {
			// А этот код выполнится дочерним процессом

			static::initWork($this->currentIndex, $step);

			sleep(1);
			exit();
		}

		return true;
	}

	private function killSlowProcess() {
		$maxexecutetime = 20*60; //20 minutes
		$currenttime = time();

		foreach($this->currentJobs as $job){
			$pid = $job['pid'];

			if(($currenttime -$job['inittime']) > $maxexecutetime) {
				echo 'kill' . $pid . "\n";
				exec('kill -9 '. $pid);
			}
		}

	}

	private static function initWork($index, $limit) {
		//static::consoleLog("start " . getmypid());


		$START_INDEX = $index;
		$END_INDEX = $index + $limit;

		include_once("/usr/local/www/kidcity/files/scripts/kidcity/fio.php");

		initNames($START_INDEX, $END_INDEX);

		//static::consoleLog("finish " . getmypid());
	}

	private static function consoleLog($text){
		echo $text."\n";
		return true;
	}

	public function childSignalHandler($signo, $pid = null, $status = null) {

		switch ($signo) {
			case SIGTERM:
				// При получении сигнала завершения работы устанавливаем флаг
				$this->stop_server = true;
				break;
			case SIGCHLD:
				// При получении сигнала от дочернего процесса
				if (!$pid) {
					$pid = pcntl_waitpid(-1, $status, WNOHANG);
				}

				//echo 'finish work PID ' . $pid . "\n";

				// Пока есть завершенные дочерние процессы
				while ($pid > 0) {
					if ($pid && isset($this->currentJobs[$pid])) {
						// Удаляем дочерние процессы из списка
						unset($this->currentJobs[$pid]);
					}
					$pid = pcntl_waitpid(-1, $status, WNOHANG);
				}

				//$this->countJobs();
				break;

			default:
				break;
		}
	}

	private function countJobs() {
		echo 'current count jobs: ' . count($this->currentJobs) . "\n";
	}
}

$daemon = new Daemon();
$daemon->run();