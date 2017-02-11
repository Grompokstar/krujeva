<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'production';

require "$APP_PATH/bin/glonass.php";

$items = [
'1.0 - черный',
'2.0 - брюнет',
'3.0 - темный шатен',
'4.0 - шатен',
'5.0 - светлый шатен',
'6.0 - темный блондин',
'7.0 - блондин',
'8.0 - светлый блондин',
'9.0 - очень светлый блондин',
'10.0 - очень очень светлый блондин',
'3.07 - брюнет',
'4.07 - кофейный',
'5.07 - шоколадный',
'6.07 - сливочная помадка',
'7.07 - блондин легкий бежевый',
'8.07 - светлый блондин легкий бежевый',
'9.07 - очень светлый песочный блондин',
'1.01 - иссиня-черный',
'5.2 - светлый шатен пепельный',
'6.2 - темный блондин пепельный',
'7.2 - блондин пепельный',
'8.2 - светлый блондин пепельный',
'9.2 - очень светлый блондин-пепельный',
'10.2 - очень светлый блондин-пепельный',
'7.43 - золотистый блондин красное дерево',
'4.3 - каштановый золотистый',
'5.3 - светлый шатен золотистый',
'6.3 - темный блондин золотистый',
'8.3 - светлый блондин золотистый',
'9.3 - очень светлый блондин золотистый',
'5.4 - светлый шатен красное дерево',
'6.44 - темный блондин глубокий красное дерево',
'6.43 - золотистый блондин красное дерево',
'4.58 - шатен красно-фиолетовый',
'5.5 - светлый шатен красный',
'6.55 - темный блондин глубокий красный',
'3.85 - темный шатен фиолетово-красный',
'4.80 - шатен интенсивно-фиолетовый',
'10.8 - очень очень светлый блондин фиолетовый',
'6.6 - темный блондин медный',
'7.66 - блондин глубокий медный',
'10.17 - очень светлый блондин-платиновый',
'11.00 - экстра светлый блондин',
'11.20 - экстра светлый блондин пепельный',
'00.18 - фиолетовый холодный',];

$fromId = 3752;
$desc = 'Профессиональная перманентная краска для волос без аммиака на базе фруктовых масел.
Гарантирует великолепный цвет, идиальное закрашивание седых волос и стойкий результат. Идеально подходит для окрашивания тонких, поврежденных и чувствительных волос. Содержит питательные и увлажняющие масла (виноградное, масло из косточек персика и Жожоба).';
$primenenie = 'Пропорция для смешивания 1:1,5 – 1:2(осветляющие & тонирование)';

foreach ($items as $item) {

	$product = \Krujeva\Data\Products::get($fromId);

	$product['oldid'] = $product['id'];

	unset($product['id']);

	$product['properties'] = [30 => trim($item), 31 => 45, 32 => $desc, 33 => $primenenie];

	$p = \Krujeva\Data\Products::insert($product);
}