<?php


$APP_PATH = realpath(__DIR__ . '/../../../..');
$APP_NAME = 'kidcityproduction';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('kidcityproduction', ['configDir' => $APP_PATH . '/configuration', 'console']);


class EmployeeAmount {


	public function init() {

		$currentDate = date('Y-m-d');
		$beforeDate = date('Y-m-d', strtotime(date('Y-m-d') . ' -1day'));

		//$dates = $this->explodeDateInterval('01.09.2015', '31.12.2015');
		$dates = $this->explodeDateInterval($beforeDate, $currentDate);

		foreach ($dates as $date) {
			$this->initDate($date);
		}
	}

	public function explodeDateInterval($startDate = '', $endDate = '') {

		$dates = [];

		if (!$startDate || !$endDate) {
			return $dates;
		}

		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);

		if ($endDate < $startDate) {
			return $dates;
		}

		$dayStep = 60 * 60 * 24; // 1 day
		for ($d = $startDate; $d <= $endDate; $d = $d + $dayStep) {
			$dates[] = date('Y-m-d', $d);
		}

		return $dates;
	}


	public function initDate($date) {
		echo $date;

		$enters = \City\Data\EmployeeEnters::select([
			'join' => [
				[
					'table' => 'kidcity.playgrounds',
					'on' => 'playgrounds.id = employeeenters.playgroundid'

				]
			],
			'where' => 'employeeenters.fromdatetime >= $1 and employeeenters.fromdatetime <= $2',
			'data' => [$date.' 00:00', $date.' 23:59']
		]);

		if (!count($enters)) {
			return;
		}

		foreach ($enters as $enter) {

			$fromdatetime = $enter['factfromdatetime'];

			$todatetime = $enter['todatetime'];

			$periodplaygroundamount = $this->getVisitsAmount($fromdatetime, $todatetime, $enter['entersessionid']);

			$persentData = null;

			if ($enter['period'] == \City\Periods::FullTime) {

				$fixEnterAmount = (float)$enter['fulltimeenteramount'];

				$persentData = JSON::parse($enter['fulltimepersents']);

			} else {

				$fixEnterAmount = (float)$enter['pathtimeenteramount'];

				$persentData = JSON::parse($enter['pathtimepersents']);
			}

			$persent = $this->getPersent($periodplaygroundamount, $persentData);

			$persentAmount = (int)($periodplaygroundamount * $persent / 100);



			$summAmount = $fixEnterAmount + $persentAmount;

			$data = [
				'date' => date('Y-m-d', strtotime($enter['fromdatetime'])),
				'weekofyear' => date('Y_W', strtotime($enter['fromdatetime'])),
				'employeeid' => $enter['userid'],
				'playgroundid' => $enter['playgroundid'],
				'period' => $enter['period'],
				'partday' => $enter['partday'],
				'amount' => $summAmount,
				'periodplaygroundamount' => $periodplaygroundamount
			];

			var_dump($data);

			$this->saveDateEnter($data);
		}
	}

	public function saveDateEnter($data) {
		$this->removeDateEnter($data['date'], $data['playgroundid'], $data['employeeid']);

		\City\Data\Accruals::insert($data);
	}

	public function removeDateEnter($date, $playgroundid, $employeeid) {

		\City\Data\Accruals::removeSet([
			'where' => 'date = $1 and employeeid = $2 and playgroundid = $3',
			'data' => [$date, $employeeid, $playgroundid]
		]);

	}

	public function getPersent($amount, $list) {

		if (!is_array($list)) {
			return 0;
		}

		$min = null;
		$minPersent = null;

		foreach ($list as $item) {

			if ($amount > $item['amount']) {
				continue;
			}

			if ($min == null || $min > $item['amount']) {
				$min = $item['amount'];
				$minPersent = $item['persent'];
			}
		}

		return $minPersent ? $minPersent : 0;
	}

	public function getVisitsAmount($from, $to, $entersessionid) {

		return (int) \City\Data\Visits::select([
			'fields' => 'sum(price)',
			'result' => 'scalar',
			'where' => 'fromdatetime >= $1 and fromdatetime <= $2 and entersessionid = $3',
			'data' => [$from, $to, $entersessionid]
		]);
	}


}

$a = new EmployeeAmount();
$a->init();
