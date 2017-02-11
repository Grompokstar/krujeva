<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

require "$APP_PATH/extensions/PHPExcel_1.8.0/PHPExcel.php";

class Loader {
	protected $mup = 264;
	public $lines = [];

	public function __construct($filename, $startRows = 1) {

		if (!is_file($filename)) {
			throw new \Exception("File cannot be read!");
		}

		$this->lines = $this->getLines($filename, $startRows);
	}

	protected function val($val, $quotes = true) {

		$val = trim($val);

		if ($quotes && $val && $val[0] == '"' && $val[strlen($val) - 1] == '"') {
			$val = str_replace('""', '"', substr($val, 1, strlen($val) - 2));
		}

		return mb_strlen($val) ? $val : null;
	}

	protected function load($file_url) {

		try {
			$objPHPExcel = \PHPExcel_IOFactory::load($file_url);
		}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}

		return $objPHPExcel;
	}

	protected function getLines($filename, $startRows) {

		$objPHPExcel = $this->load($filename);
		$data = array();
		$finnalyData = array();

		$worksheetcount = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$highestRow = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

			for ($row = $startRows; $row <= $highestRow; ++$row) {
				for ($col = 0; $col < $highestColumnIndex; ++$col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();

					$data[$worksheetcount][$row][$col] = $val;
				}
			}

			$worksheetcount++;
		}

		foreach ($data as $worksheet) {
			foreach ($worksheet as $row) {
				$finnalyData[] = $row;
			}
		}

		return $finnalyData;
	}
}

$country = new Loader('country.xls', 2);
$countryIndex = [];

\Dict\Data\Countries::queryRows('delete from dict.countries');

foreach ($country->lines as $country) {

	$countryIndex[$country[0]] = \Dict\Data\Countries::insert(['name' => $country[2]]);
}




$areas = new Loader('region.xls', 2);
$areasIndex = [];

\Dict\Data\Countries::queryRows('delete from dict.areas');

foreach ($areas->lines as $area) {

	$areasIndex[$area[0]] = \Dict\Data\Areas::insert([
		'countryid' => $countryIndex[$area[1]]['id'],
		'name' => $area[3],
	]);
}

$cities = new Loader('city.xls', 2);

\Dict\Data\Countries::queryRows('delete from dict.cities');

foreach ($cities->lines as $city) {

	 \Dict\Data\Cities::insert([
		 'areaid' => $areasIndex[$city[2]]['id'],
		 'name' => $city[3],
	 ]);
}