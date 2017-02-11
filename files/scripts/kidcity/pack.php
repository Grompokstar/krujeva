<?php
$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'kidcity';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('kidcity', ['configDir' => $APP_PATH . '/configuration', 'console']);





$moveTo = '/Users/aleksejsamysev/Documents/projects/tabletapp/kidcity/www/';

$css = [
	'css/utils/picker/jquery-ui-1.10.4.custom.css',
	'css/utils/picker/custom-picker.css',

	'css/base/main.css',
	'css/kidcitytablet/main.css',
];


$js = [
	'js/libs/ejs_production.js',
	'js/libs/moment.min.js',

	'js/libs/jquery-2.1.4.min.js',
	'js/libs/jquery.transit.min.js',
	'js/libs/jquery.mask.js',
	'js/libs/end.animate.js',
	'js/libs/jquery-ui.min.js',
	'js/libs/jquery.ui.datepicker-ru.min.js',

	'js/libs/fastclick.js',

	'js/core/Core.js',
	'js/core/Enums.js',
	'js/core/Module.js',
	'js/core/Util.js',
	'js/core/Xhr.js',
	'js/core/Globals.js',
	'js/core/Cookie.js',
	'js/core/Alert.js',
	'js/core/Popup.js',
	'js/core/Loader.js',
	'js/core/LocalStorage.js',
	'js/core/Html.js',
	'js/core/KeyBoard.js',
	'js/core/NumKeyboard.js',
	'js/core/KeyboardElement.js',

	['includeEJS', '/public/templates/',],

	['includeJS', '/public/js/CityTablet/Data/', '/public/js/CityTablet/Application.js']
];

$static = new \Compressor\IncludeStatic('css');

$css = $static->init('css', '', $css, ['returnData' => true]);

$res = file_put_contents($moveTo.'css/index.css', $css);


$static = new \Compressor\IncludeStatic('js');

$js = $static->init('js', '', $js, ['returnData' => true]);

$res = file_put_contents($moveTo . 'js/index.js', $js);

var_dump($res);