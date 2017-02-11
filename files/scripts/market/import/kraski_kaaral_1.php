<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'production';

require "$APP_PATH/bin/glonass.php";

$items = [
'2.0  коричневый',
'3.0  темный каштан',
'4.0  каштан',
'5.0  светлый каштан',
'6.0  темный блондин',
'7.0  блондин',
'8.0  светлый блондин',
'9.0 очень светлый блондин',
'10.0  платиновый блондин',
'5.00 светлый интенсивный каштан (дуб)',
'6.00 темный интенсивный блондин (земля)',
'7.00 интенсивный блондин (зерно)',
'8.00 светлый интенсивный блондин (песок)',
'9.00 очень светлый интенсивный блондин (жемчуг)',
'9.10 очень светлый блондин пепельный глубокий',
'10.10 светлый платиновый блондин',
'2.11  синяя ночь',
'4.1 пепельный каштан',
'6.1  темно-пепельный блондин',
'7.1  пепельный блондин',
'8.1 светло-пепельный блондин',
'9.1  очень свет  пепельный блондин',
'10.1  светло-лунный блондин',
'2.20  фиолетово-синий',
'4.20  интенсивный фиолетовый каштан',
'4.2 фиолетовый каштан',
'5.2  светлый фиолетовый каштан',
'6.2 темный фиолетовый блондин',
'9.02  очень светлый натурально-фиолетовый блондин',
'1.62 черный красно- фиолетовый',
'8.62 светлый блондин красно-фиолетовый',
'7.32 золотисто-фиолетовый блондин',
'8.33  глубокий  светлый золотистый блондин',
'9.32  очень светлый золотисто-фиолетовый блондин',
'5.3  светлый золотистый каштан',
'6.3  темный  золотистый блондин',
'7.3  золотистый блондин',
'9.3  очень свет  золотистый блондин',
'9.43 очень светлый золотистый блондин',
'5.40   интенсивно-медный каштан',
'Red contrast  красный контраст',
'Coppery contrast  медный контраст',
'7.4   медный блондин',
'7.45 медно-махагоновый блондин',
'7.46   медно-красный блондин',
'8.44  светлый интенсивно-медный блондин',
'5.62 светлый красно-фиолетовый каштан (вишня)',
'6.6 темный красный блондин (мерло)',
'7.6 красный блондин',
'5.6  светлый красный каштан',
'5.60 светлый интенсивно-красный каштан',
'5.66  светлый глубокий красный каштан',
'6.60  темный интенсивно-красный каштан',
'6.66 темный глубокий красный блондин',
'7.60  интенсивно-красный блондин',
'7.66 глубокий красный блондин',
'7.34 золотисто-медный блондин (круассан)',
'7.44 интенсивно-медный блондин (чай)',
'4.5  махагоновый каштан',
'5.5  светлый махагоновый каштан',
'5.52  светлый махагоново-фиолетовый каштан',
'6.5  темный махагоновый каштан',
'8.5 светлый  махагоновый блондин',
'6.56  темный махагоново-красный блондин',
'8.56 светлый махагоново-красный блондин',
'7.65 красно-махагоновый блондин',
'4.36  золотисто-красный каштан (шоколад)',
'5.53 светлый махагоново-золотистый каштан (кофе)',
'6.35 темный золотисто-махагоновый блондин (интенсивный орех)',
'7.31 золотисто-пепельный блондин (карамель)',
'7.53 махагоново-золотистый блондин  (орех)',
'5.18 светло коричневый пепельный каштан',
'5.38 светлый каштан золотисто-коричневый',
'11  жемчужно- белый',
'11.1 экстра светлый пепельный блондин',
'11.2 экстра светлый фиолетовый блондин',
'11.3 экстра светлый золотистый блондин',
'11.30 экстра светлый интенсивно-золотистый блондин',
'12.13 экстра светлый пепельно-золотистый блондин',
'12.21 экстра светлый фиолетово-пепельный блондин',
'12.32 экстра светлый золотисто-фиолетовый блондин',
'0.00 нейтральный (000)',
'T-AG серебристо-серый корректор (002)',
'T-M фиолетовый корректор (02)',
'T-D золотистый корректор (03)',
'T-RO красный корректор (06)',
'T-B синий корректор (09)',
];

$fromId = 3523;
$desc = 'Перманентный краситель, в состав которого входит эксклюзивный MPT Complex, гарантирует получение более насыщенного глубокого цвета волос после окрашивания. Ухаживающие компоненты уменьшают дискомфортные ощущения во время окрашивания и делают процесс окрашивания максимально деликатным и комфортным для клиента и мастера. Содержит экстракт алоэ вера, обладающий увлажняющим действием, кокосовое масло для дополнительной защиты волос и провитамин В5 для поддержания внутренней структуры волоса. Нумерология цвета: 1 цифра – глубина тона, 2 цифра – основной оттенок, 3 цифра – нюанс основного оттенка.';
$primenenie = 'Стойкое окрашивание: Cмешивание с окислителем DEV Plus 1:1 (1 часть крема-красителя + 1 часть окислителя)
Для тонирования волос – окислитель DEV Plus 6 vol.
Для окрашивания тон в тон с затемнением – окислитель DEV Plus 10 vol.
Для окрашивания с осветлением на 1 уровень – окислитель DEV Plus 20 vol. Д
ля окрашивания с осветлением на 2–3 уровня – окислитель DEV Plus 30 vol
Для окрашивания с осветлением на 3–4 уровня – окислитель DEV Plus 40 vol. + корректор.';

foreach ($items as $item) {

$product = \Krujeva\Data\Products::get($fromId);

$product['oldid'] = $product['id'];

unset($product['id']);

$product['properties'] = [30 => trim($item), 31 => 45, 32 => $desc, 33 => $primenenie];

$p = \Krujeva\Data\Products::insert($product);
}