<?php
$APP_PATH = realpath(__DIR__ . '/../../..');
$APP_NAME = 'dev';

date_default_timezone_set("UTC");
mb_internal_encoding('UTF-8');

include $APP_PATH . '/application/app.php';

appLoad('Globals/System');
appLoad('Globals/Security');
appLoad('Globals/Data');
appLoad('Globals/Date');

app('dev', ['configDir' => $APP_PATH . '/configuration', 'console']);





$moveTo = '/Users/aleksejsamysev/Documents/projects/cordovaapps/kvik/www/';
$moveToIos = '/Users/aleksejsamysev/Documents/projects/cordovaapps/kvikios3/www/';

$css = [
    '/public/css/utils/photoswipe.css',
    '/public/css/utils/swiper.min.css',
    '/public/css/marketmobile/main.css',
];


$js = [
    'js/libs/ejs_production.js',

    'js/libs/jquery-2.1.4.min.js',
    'js/libs/end.animate.js',
    'js/libs/iscroll.js',
    'js/libs/areas.js',

    'js/libs/photoswipe/main.js',
    'js/libs/photoswipe/ui.js',
    'js/libs/swiper.jquery.min.js',

    'js/core/Core.js',
    'js/core/Enums.js',
    'js/core/Util.js',
    'js/core/Module.js',
    'js/core/Events.js',
    'js/core/Xhr.js',
    'js/core/Globals.js',
    'js/core/Cookie.js',
    'js/core/Alert.js',
    'js/core/Loader.js',
    'js/core/LocalStorage.js',
    'js/core/Html.js',

    ['includeEJS',
        '/public/templates/KrujevaMobile/',
        '/public/templates/Utils/',
    ],

    ['includeJS',
        '/public/js/KrujevaMobile/Data/',
        '/public/js/KrujevaMobile/Application.js'
    ]
];

$static = new \Compressor\IncludeStatic('css');

$css = $static->init('css', '', $css, ['returnData' => true]);

//@css replace path
$css = str_replace('/public/', '../', $css);

$res = file_put_contents($moveTo.'css/index.css', $css);
$res = file_put_contents($moveToIos.'css/index.css', $css);



$static = new \Compressor\IncludeStatic('js');

$js = $static->init('js', '', $js, ['returnData' => true]);

//js replace path
$js = str_replace('public/', '', $js);
$js = str_replace('/img/', 'img/', $js);

$res = file_put_contents($moveTo . 'js/index.js', $js);
$res = file_put_contents($moveToIos . 'js/index.js', $js);


var_dump($res);