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




class PlaygroundStatistic {

	public function init() {

		$currentDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1day')). ' 00:00';
		$beforeDate = date('Y-m-d', strtotime(date('Y-m-d') . ' -10day')).' 00:00';

		//$dates = $this->explodeDateInterval('01.09.2015 00:00', '31.12.2015 00:00');
		$dates = $this->explodeDateInterval($beforeDate, $currentDate);

		$fromdatetime = null;

		$playgrounds = \City\Data\Playgrounds::select();

		foreach ($dates as $date) {

			if ($fromdatetime) {
				$this->initDate($fromdatetime, $date, $playgrounds);
			}

			$fromdatetime = $date;
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

		$dayStep = 60 * 60; // 1 hour
		for ($d = $startDate; $d <= $endDate; $d = $d + $dayStep) {
			$dates[] = date('Y-m-d H:i', $d);
		}

		return $dates;
	}

	public function initDate($fromdatetime, $todateime, $playgrounds = []) {

		foreach ($playgrounds as $playground) {

			$this->formDataPlayground($playground, $fromdatetime, $todateime);

		}

	}

	private function formDataPlayground($playground, $fromdatetime, $todateime) {

		echo $fromdatetime."\n";

		$this->clearStatistic($playground['id'], $fromdatetime);

		//@city
		if (!$playground['cityid']) {
			return;
		}

		$city = \City\Data\Cities::get($playground['cityid']);

		if (!$city) {
			return;
		}

		//@$absolutedatetime
		$absolutedatetime = Utils::localeDatetime(strtotime($fromdatetime), $city['timezone'] * -1);


		$visits = \City\Data\Visits::select([
			'fields' => 'price',
			'where' => 'playgroundid = $1 and fromdatetime >= $2 and fromdatetime < $3',
			'data' => [$playground['id'], $fromdatetime, $todateime]
		]);

		//@kids
		$kids = count($visits);

		//@amount
		$amount = 0;

		foreach ($visits as $visit) {
			$amount += (float)$visit['price'];
		}

		//@employees
		$employees = \City\Data\EmployeeEnters::select([
			'fields' => 'count(1)',
			'result' => 'scalar',
			'where' => 'playgroundid = $1 and fromdatetime >= $2 and fromdatetime < $3',
			'data' => [$playground['id'], $fromdatetime, $todateime]
		]);


		//@isopened
		$isopened = (bool)\City\Data\EmployeeEnters::select([
			'fields' => 'count(1)',
			'result' => 'scalar',
			'where' => 'playgroundid = $1 and (fromdatetime, todatetime) overlaps ($2, $3)',
			'data' => [$playground['id'], $fromdatetime, $todateime]
		]);

		$data = [
			'datetime' => $fromdatetime,
			'absolutedatetime' => $absolutedatetime,
			'playgroundid' => $playground['id'],
			'amount' => $amount,
			'kids' => $kids,
			'employees' => $employees,
			'isopened' => $isopened
		];

		\City\Data\PlaygroundStatistics::insert($data);
	}

	private function clearStatistic($playgroundid, $datetime) {

		\City\Data\PlaygroundStatistics::removeSet([
			'where' => 'datetime = $1  and playgroundid = $2',
			'data' => [$datetime, $playgroundid]
		]);
	}
}

$a = new PlaygroundStatistic();
$a->init();
