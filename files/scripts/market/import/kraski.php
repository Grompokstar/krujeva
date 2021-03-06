<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'dev';

require "$APP_PATH/bin/glonass.php";

$items = [
	'10.0 Светлый блондин натуральный',
	'9.0 Блондин натуральный',
	'8.0 Светло - русый натуральный',
	'7.0 Средне - русый натуральный',
	'6.0 Темно - русый натуральный',
	'5.0 Светлый шатен',
	'4.0 Шатен',
	'3.0 Темный шатен',
	'1.0 Черный',
	'8.00 Светло - русый',
	'7.00 Средне - русый',
	'6.00 Темно - русый',
	'5.00 Светлый шатен',
	'4.00 Шатен',
	'8.03 Светло - русый золотистый',
	'7.03 Средне - русый золотистый',
	'6.03 Темно - русый золотистый',
	'5.03 Светлый шатен золотистый',
	'9.71 Блондин холодный',
	'8.71 Светло - русый холодный',
	'7.71 Средне - русый холодный',
	'6.71 Темно - русый холодный',
	'10.1 Светлый блондин пепельный',
	'9.1 Блондин пепельный',
	'8.1 Светло - русый пепельный',
	'7.1 Средне - русый пепельный',
	'6.1 Темно - русый пепельный',
	'5.1 Светлый шатен пепельный',
	'4.1 Шатен пепельный',
	'1.1 Иссиня - черный',
	'8.34 Светло - русый золотисто - медный',
	'6.34 Темно - русый золотисто - медный',
	'9.33 Блондин насыщенный золотистый',
	'9.3 Блондин золотистый',
	'8.33 Светло - русый насыщенный золотистый',
	'8.3 Светло - русый золотистый',
	'7.33 Средне - русый насыщенный золотистый',
	'7.3 Средне - русый золотистый',
	'6.3 Темно - русый золотистый',
	'5.3 Светлый шатен золотистый',
	'4.3 Шатен золотистый',
	'7.31 Средне - русый золотисто - пепельный',
	'6.31 Темно - русый золотисто - пепельный',
	'5.31 Светлый шатен золотисто - пепельный',
	'7.36 Средне - русый золотисто - фиолетовый',
	'6.36 Темно - русый золотисто - фиолетовый',
	'6.35 Темно - русый золотисто - красный',
	'5.38 Светлый шатен золотистый махагон',
	'4.38 Шатен золотистый махагон',
	'6.86 Темно - русый махагон фиолетовый',
	'5.86 Светлый шатен махагон фиолетовый',
	'4.86 Шатен махагон фиолетовый',
	'8.7 Светло - русый коричневый',
	'7.7 Средне - русый коричневый',
	'6.7 Темно - русый коричневый',
	'5.7 Светлый шатен коричневый',
	'6.77 Темно - русый насыщенный коричневый',
	'5.77 Светлый шатен насыщенный коричневый',
	'4.77 Шатен насыщенный коричневый',
	'9.13 Блондин пепельно - золотистый',
	'8.13 Светло - русый пепельно - золотистый',
	'7.13 Средне - русый пепельно - золотистый',
	'6.13 Темно - русый пепельно - золотистый',
	'6.15 Темно - русый пепельно - красный',
	'5.16 Светлый шатен пепельно - фиолетовый',
	'4.16 Шатен пепельно - фиолетовый',
	'10.06 Светлый блондин жемчужный',
	'9.06 Блондин жемчужный',
	'8.4 Светло - русый медный',
	'7.4 Средне - русый медный',
	'6.4 Темно - русый медный',
	'5.4 Светлый шатен медный',
	'7.44 Средне - русый насыщенный медный',
	'7.43 Средне - русый медно - золотистый',
	'6.43 Темно - русый медно - золотистый',
	'5.43 Светлый шатен медно - золотистый',
	'7.45 Средне - русый медно - красный',
	'6.45 Темно - русый медно красный',
	'8.48 Светло - русый медно - махагоновый',
	'7.48 Средне - русый медно - махагоновый',
	'6.48 Темно - русый медно - махагоновый',
	'6.56 Темно - русый красно - фиолетовый',
	'5.56 Светлый шатен красно - фиолетовый',
	'7.54 Средне - русый красно - медный',
	'6.54 Темно - русый красно - медный',
	'8.55 Светло - русый красный насыщенный',
	'7.55 Средне - русый красный насыщенный',
	'6.55 Темно - русый красный насыщенный',
	'5.8 Светлый шатен махагон',
	'4.8 Шатен махагон',
	'9.66 Блондин насыщенный фиолетовый',
	'6.65 Темно - русый фиолетово - красный',
	'5.6 Светлый шатен фиолетовый',
	'4.6 Шатен фиолетовый',
	'12.30 Блондин золотистый',
	'12.06 Блондин платиновый',
	'12.01 Блондин пепельный',
	'12.00 Блондин натуральный',
	'12.11 Блондин серебристый',
	'12.70 Блондин коричневый',
	'12.81 Блондин махагоново - пепельный',
	'12.61 Блондин фиолетово - пепельный',
	'12.16 Блондин пепельно - фиолетовый',
	'0.1 Голубой',
	'0.2 Зеленый',
	'0.3 Желтый',
	'0.4 Оранжевый',
	'0.5 Красный',
	'0.6 Фиолетовый',
	'9.00 Блондин интенсивный',
	'9.7 Блондин натуральный коричневый',
	'4.7 Шатен коричневый',
	'11.00 Супер блондин натуральный',
	'11.01 Супер блондин пепельный',
	'11.16 Супер блондин пепельно - фиолетовый',
	'11.17 Супер блондин холодный',
	'11.65 Супер блондин фиолетово - красный',
	'10.3 Светлый блондин золотистый',
	'10.04 Светлый блондин медный',
	'10.12 Светлый блондин пепельно - перламутровый',
	'8.12 Светло русый пепельно - перламутровый',
	'6.12 Темно русый пепельно - перламутровый',
	'10.16 Светлый блондин пепельно - фиолетовый',
	'9.16 Блондин пепельно - фиолетовый',
	'11.166 Супер блондин пепельно - фиолетовый жемчуг',
	'10.65 Светлый блондин фиолетово - красный',
	'9.65 Блондин фиолетово - красный',
	'9.44 Блондин насыщенный медный',
	'8.44 Светло - русый насыщенный медный',
	'10.7 Светлый блондин коричневый',
	'7.77 Средне русый насыщенный коричневый',
	'7.34 Средне - русый золотистый медный'
];


foreach ($items as $item) {

	$product = \Krujeva\Data\Products::get(179);

	$product['oldid'] = $product['id'];

	unset($product['id']);

	$product['properties'] = [
		30 => $item,
		31 => 45,
		32 => 'Интеллектуальность NEXXT COLOR CENTURY заключается в опциях красителя, а именно:
- Максимально бережно воздействует при окрашивание.
- Идеально попадает в цвет.
- Интеллектуальный комплекс VitaProtect восстанавливает поврежденную структуру волоса уже в процессе окрашивания, обеспечивая уникальный блеск и эластичность.
- Благодаря инновационной системе iNanocolor пигменты работают не только на химическом, но и на физическом уровне. За счет этого - снижение уровня аммиака до минимального. Как результат - более щадящее окрашивание и здоровые волосы.
- Passiflora incarnata flower extract - экстракт пассифлоры в составе питает и увлажняет волосы в процессе окрашивания.
- Hydrolyzed Sweet Almond Protein - гидролизованный протеин сладкого миндаля в составе красителя создает на поверхности кожи и волоса защитный слой.',
		33 => '1. В неметаллической посуде смешайте NEXXT крем - краску уход с NEXXT крем - окислителем в пропорции 1:1. Выбор концентрации NEXXT крема - окислителя 1,5%, 3%, 9% зависит от желаемого уровня осветления.
2. Красящую смесь нанести на сухие волосы.
3. Время выдержки составляет 40 минут. Рекомендуемое время выдержки является ориентировочным и по мере необходимости может быть увеличено.
4. По окончании времени воздействия слегка съемульгировать красящую смесь с теплой водой и тщательно промыть волосы водой.
5.Затем равномерно нанести NEXXT Colour шампунь на влажные волосы, вспенить и тщательно смыть.
6. Для того, чтобы стабилизировать цвет и остановить окислительные реакции, равномерно распределить на волосы NEXXT Colour кондиционер, оставить воздействовать несколько минут, затем тщательно промыть водой.'
	];

	$p = \Krujeva\Data\Products::insert($product);
}
