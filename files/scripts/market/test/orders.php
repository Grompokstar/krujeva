<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

require "$APP_PATH/extensions/PHPExcel_1.8.0/PHPExcel.php";



\Krujeva\Message::dealerNewOrder(1, 2);

return;

$order = [
	'status' => 1,
	'dealerid' => 2,
	'clientid' => 38,
	'comment' => 'we',
	'isnew' => 1,
	'totalprice' => 1200,
	'phone' => '+343 423 423 423 4',
	'barbershopid' => 28,
	'createddatetime' => "2016-04-20 13:50:18",
	'localcreateddatetime' => "2016-04-20 16:50:18",
];

$orderproduct = [
	'productid' => '163',
	'count' => '3',
	'price' => '123'
];

for($i = 0; $i < 100000; $i++) {

	echo $i."\n";

	$od = \Krujeva\Data\Orders::insert($order);

	$orderproduct['orderid'] = $od['id'];

	$od = \Krujeva\Data\OrderProducts::insert($orderproduct);
}