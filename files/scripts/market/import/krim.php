<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";




//KRIM
/*
$cities = '["Симферополь","Алупка","Алушта","Армянск","Бахчисарай","Белогорск","Гаспра","Гвардейское","Грэсовский","Джанкой","Евпатория","Жаворонки","Инкерман","Каховское","Керчь","Красногвардейское","Красноперекопск","Кубанское","Морская","Новый Свет","Октябрьское","Ореанда","Приморский","Саки","Севастополь","Северная","Симоненко","Старый Крым","Судак","Украинка","Феодосия","Черноморское","Чистенькая","Щёлкино","Ялта"]';

$cities = \JSON::parse($cities);

$regionid = 1633;
*/




//TATARSTAN
$cities = '["Казань","Агрыз","Азнакаево","Айша","Аксубаево","Актаныш","Актюбинский","Алексеевское","Альметьевск","Апастово","Арск","Бавлы","Базарные Матаки","Балтаси","Бетьки","Богатые Сабы","Болгар","Большая Атня","Бугульма","Буинск","Васильево","Верхний Услон","Высокая Гора","Джалиль","Елабуга","Заинск","Зеленодольск","Камские Поляны","Камское Устье","Карабаш","Кукмор","Лаишево","Лениногорск","Мамадыш","Менделеевск","Мензелинск","Муслюмово","Набережные Челны","Нижнекамск","Нижние Вязовые","Нижняя Мактама","Новошешминск","Нурлат","Осиново","Пестрецы","Русский Акташ","Рыбная Слобода","Сарманово","Старое Дрожжаное","Столбищи","Тетюши","Уруссу","Черемшан","Чистополь","Шемордан"]';

$cities = \JSON::parse($cities);

$regionid = 982;


foreach ($cities as $cityname) {

	$oldItem = \Dict\Data\Cities::select([
		'result' => 'row',
		'where' => "areaid = $1 and lower(name) like lower('%' || $2 || '%')",
		'limit' => 1,
		'data' => [$regionid, $cityname]
	]);

	if (!$oldItem) {

		\Dict\Data\Cities::insert([
			'name' => $cityname,
			'areaid' => $regionid
		]);

		var_dump($cityname);
	}

}

