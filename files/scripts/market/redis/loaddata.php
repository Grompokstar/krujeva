<?php

$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

class LoadRedisData {

	public function init() {

		echo "Dealers \n";
		\Krujeva\RedisData\Dealers::loadData();

		echo "BrandCategory \n";
		\Krujeva\RedisData\BrandCategory::loadData();

		echo "Products \n";
		$r = \Krujeva\Elastic\Product::setSettings();
		\Krujeva\RedisData\Products::loadData();

		echo "History \n";
		\Krujeva\RedisData\HairOrdersHistory::loadData();
	}
}

$d = \Krujeva\RedisData\HairOrdersHistory::loadData();
var_dump(($d));


//$a = new LoadRedisData();
//$a->init();
