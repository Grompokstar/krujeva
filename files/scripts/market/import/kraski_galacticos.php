<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'production';

require "$APP_PATH/bin/glonass.php";

$items = [
'1.0 Черный','1.1 Иссиня-черный','10.0 Светлый блондин','10.1 Светлый блондин пепельный','10.2 Светлый блондин перламутровый','1000 Спец блонд натуральный','1001 Спец блонд пепельный','1016 Спец блонд пепельно-фиолетовый','1017 Спец блонд холодный','1065 Спец блонд фиолетово-красный','12.00 Экстра блонд натуральный','12.01 Экстра блонд пепельный','12.11 Экстра блонд серебристый','12.16 Экстра блонд пепельно-фиолетовый','12.2  Экстра блонд перламутровый','12.30 Экстра блонд золотистый','12.61 Экстра блонд фиолетово-пепельный','12.70 Экстра блонд коричневый','12.81 Экстра блонд махагоново-пепельный','3.0 Темный шатен','4.0 Шатен','4.00 Темный шатен интенсивный','4.1 Шатен пепельный','4.16  Шатен пепельно-фиолетовый','4.6 Шатен фиолетовый','4.7 Шатен коричневый','4.77  Шатен насыщенный коричневый','4.8 Шатен махагон фиолетовый','5.0 Светлый шатен','5.00 Светлый шатен интенсивный','5.03 Светлый шатен золотистый','5.1 Светлый шатен пепельный','5.16 Светлый шатен пепельно-фиолетовый','5.3 Светлый шатен золотистый','5.31 Светлый шатен золотисто-пепельный','5.4 Светлый шатен медный','5.43 Светлый шатен медно-золотистый','5.6 Светлый шатен фиолетовый','5.7 Светлый шатен коричневый','5.77 Светлый шатен насыщенный коричневый','5.86 Светлый шатен махагон фиолетовый','6.0 Темно-русый','6.00 Темно-русый интенсивный','6.03 Темно-русый золотистый','6.1 Темно-русый пепельный','6.13 Темно-русый пепельно-золотистый','6.3 Темно-русый золотистый','6.31 Темно-русый золотисто-пепельный','6.34 Темно-русый золотисто-медный','6.4 Темно-русый медный','6.43 Темно-русый медно-золотистый','6.48 Темно-русый медно-махагоновый','6.55 Темно-русый  красный насыщенный','6.65 Темно-русый фиолетово-красный','6.7 Темно-русый коричневый','6.71 Темно-русый холодный','6.77 Темно-русый насыщенный коричневый','6.86 Темно-русый махагон фиолетовый','7.0 Средне-русый','7.00 Русый  интенсивный','7.03 Русый золотистый','7.1 Русый пепельный','7.13 Русый пепельно-золотистый','7.3 Русый золотистый','7.30 Русый интенсивный золотистый','7.31 Средне-русый золотисто-пепельный','7.4 Русый медный','7.43 Русый медно-золотистый','7.44 Средне-русый насыщенный медный','7.48 Русый медно-махагоновый','7.55 Русый  красный насыщенный','7.7 Русый коричневый','7.71 Русый холодный','8.0 Светло-русый','8.00 Светло-русый  интенсивный','8.03 Светло-русый золотистый','8.1 Светло-русый пепельный','8.13 Светло-русый пепельно-золотистый','8.3 Светло-русый золотистый','8.30 Светло-русый  интенсивный золотистый','8.34 Светло-русый золотисто-медный','8.4 Светло-русый медный','8.48 Светло-русый медно-махагоновый','8.55 Светло-русый красный насыщенный','8.7 Светло-русый коричневый','8.71 Светло-русый холодный','9.0 Блондин','9.00 Светлый блондин интенсивный','9.1 Блондин пепельный','9.13 Блондин пепельно-золотистый','9.2 Блондин перламутровый','9.3 Блондин золотистый','9.30 Светлый блондин интенсивный золотистый','9.66 Блондин насыщенный фиолетовый','9.7 Блондин коричневый','9.71 Блондин холодный','Средне-русый коричневый интенсивный','Русый коричнево-фиолетовый','Русый медно-золотистый','Синий','Оранжевый','Темный блондин пепельно-перламутровый','Графит','Светло-русый пепельно перламутровый','Светло-русый коричнево-фиолетовый','Светло-русый медный интенсивный','Малахит','Розовый','Красный','Спец блонд усиленный пепельно-фиолетовый','Светлый блондин пепельно перламутровый','Светлый блондин пепельно-фиолетовый','Светлый блондин коричневый','Светлый блондин коричнево-фиолетовый','Светлый блондин махагон перламутровый','Светлый блондин легкий  медный','Светлый блондин фиолетово-красный','Светлый блондин пшеничный','Блондин пепельно-фиолетовый','Светлый блондин корица','Блондин медный интенсивный','Блондин золотисто-медный','Светлый блондин коричнево-фиолетовый','Блондин фиолетово-красный','Фиолетовый','Желтый','Аммиачный осветлитель','Пастельный осветлитель'
];

foreach ($items as $item) {

	$product = \Krujeva\Data\Products::get(414);

	$product['oldid'] = $product['id'];

	unset($product['id']);

	$product['properties'] = [
		30 => $item,
		31 => 45,
		32 => 'С Уникальным ухаживающим, придающим необходимую стойкость, блеск и качественную интенсивность цвета комплексом масел: сезамовое, маковое, фиговое, конопляное и ликеро-винного купажа, создающие тонкую дышащую пленку на поверхности волоса, которая работает как линза, вбирая и отражая свет, позволяя оттенкам эффектно играть и стильно переливаться. Для создания такого инновационного оптического эффекта: ликер - COINTREAU - французский апельсиновый ликер и итальянское вино d\'ASTI DOLCETTO в процессе пастеризации купажируется с ухаживающими маслами . Следует отметить, что краска для волос METROPOLIS COLOR предназначена в т . ч . для ослабленных, тонких и лишенных силы волос, ведь она не только окрашивает волосы, но и ухаживает за ними и оказывает восстанавливающее воздействие на них, за счет имеющихся масел, кератина и протеинов . Краска плотно закрашивает седину . Может быть использована для интенсивного тонирования при смешивании с 3% окислителем.',

		33 => '
		1. Стойкое окрашивание тон в тон.ю на тон или несколько тонов темнее:
Волосы предварительно не мыть. Смесь нанести на корни волос и длину одновременно.
2. Вторичное окрашивание:
Нанести готовую смесь на отросшие корни волос на 305-30 минут. Далее умеренно увлажнить волосы водой и равномерно распределить крем-краску по всей длине. После этого время воздействия еще 5-10 минут.
3. Окрашивание с осветлением (на 2-3 тона):
Отступив от корней волос 2 см, нанести смесь по всей длине. Затем нанести смесь на оставшиеся 2 см.

Интенсивное тонирование:
Для предварительно осветленных волос. Крем-краска смешивается с крем-окислителем 1.5% или 3% в пропорции 1:2. Время воздействия: 15-20 минут.

Окрашивание седых волос:
Гарантировано 100% покрытие седины. Натуральные тона серии /00 имеют уплотненный цветовой пигмент и смешиваются с окислителем 6% в пропорции 1:1.5. Время воздействия 45 минут.'
	];

	$p = \Krujeva\Data\Products::insert($product);
}