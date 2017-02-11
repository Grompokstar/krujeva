<?php
$APP_PATH = realpath(__DIR__ . '/../../../..');

$CONFIG_DIR = $APP_PATH . '/configuration';

$APP_NAME = 'production';

require "$APP_PATH/bin/glonass.php";

$items = [
'3N темный шатен ',
'4N шатен ',
'5N светлый шатен ',
'6N темный блондин ',
'7N блондин ',
'8N светлый блондин ',
'9N очень светлый блондин ',
'10N очень-очень светлый блондин ',
'11N Ультра светлый блондин ',
'1A Иссиня-черный пепельный ',
'5A светлый шатен пепельный ',
'6A темный блондин пепельный ',
'7A блондин пепельный ',
'9A очень светлый блондин пепельный ',
'11A Ультра светлый блондин пепельный ',
'11AA Ультра светлый блондин глубокий пепельный ',
'5AV светлый шатен пепельно-перламутровый ',
'7AV блондин пепельно-перламутровый ',
'8AV светлый блондин пепельно-перламутровый ',
'9AV очень светлый блондин пепельно-перламутровый ',
'10AV очень-очень светлый блондин пепельно-перламутровый ',
'5AG светлый шатен Пепельно-золотистый',
'6AG темный блондин Пепельно-золотистый',
'8AG светлый блондин Пепельно-золотистый',
'5G светлый шатен золотистый ',
'7G блондин золотистый ',
'8G светлый блондин золотистый',
'8GC светлый блондин золотистый медный',
'9G очень светлый блондин золотистый ',
'10G очень-очень светлый блондин золотистый ',
'11G Ультра светлый блондин золотистый ',
'5СG светлый шатен Медно-Золотистый',
'7СG блондин Медно-Золотистый',
'5C светлый шатен медный ',
'6С темный блондин медный ',
'7С блондин медный ',
'8С светлый блондин медный ',
'8CC светлый блондин глубокий медный ',
'4M шатен мокка ',
'5M светлый шатен мокка ',
'6M темный блондин мокка ',
'7M блондин мокка ',
'8M светлый блондин мокка ',
'9M очень светлый блондин мокка ',
'6MM темный блондин мокка мокка ',
'8MM светлый блондин мокка мокка ',
'10MM очень-очень светлый блондин мокка мокка ',
'6P темный блондин Жемчужный',
'8P светлый блондин Жемчужный',
'10P очень-очень светлый блондин Жемчужный',
'5MG светлый шатен мокка золотистый ',
'6MG темный блондин мокка золотистый ',
'7MG блондин мокка золотистый',
'5W теплый светлый шатен ',
'6W теплый темный',
'7W теплый блондин ',
'9W теплый очень светлый блондин ',
'4NW натуральный теплый шатен ',
'5NW натуральный теплый светлый шатен',
'6NW натуральный теплый темный блондин',
'7NW натуральный теплый блондин ',
'8NW натуральный теплый светлый блондин ',
'10NW очень-очень светлый блондин натуральный теплый ',
'6VR темный блондин перламутрово-красный ',
'4BC шатен коричнево-медный ',
'5BC светлый шатен коричнево-медный ',
'6BC темный блондин коричнево-медный ',
'7BC блондин коричнево-медный',
'4BR шатен коричнево-красный ',
'5BR светлый шатен коричнево-красный ',
'6BR темный блондин коричнево-красный ',
'5BV светлый шатен коричнево-перламутровый ',
'6BV темный блондин коричнево-перламутровый ',
'8RC светлый блондин красно-медный ',
'5R светлый шатен красный ',
'5RR+ светлый шатен глубокий красный+ ',
'6RC+ темный блондин красно-медный+ ',
'5RV+ светлый шатен красно-перламутровый+ ',
'7RR+ блондин глубокий красный+',
'505NA светлый шатен натуральный пепельный',
'506NA темный блондин натуральный пепельный',
'508NA светлый блондин натуральный пепельный',
'509NA очень светлый блондин натуральный пепельный',
'504N шатен 100% покрытие седины ',
'505N светлый шатен 100% покрытие седины ',
'506N темный блондин 100% покрытие седины ',
'507N блондин 100% покрытие седины ',
'508N светлый блондин 100% покрытие седины ',
'509N очень светлый блондин 100% покрытие седины ',
'505G светлый шатен золотистый 100% покрытие седины ',
'507G блондин золотистый 100% покрытие седины',
'509G очень светлый блондин золотистый 100% покрытие седины',];

$fromId = 3373;
$desc = 'Инновационные технологии в новом красителе позволяют добиться непревзойденного результата и желаемого оттенка с минимальными усилиями. SOCOLOR.beauty любим многими колористами за возможность создавать насыщенные оттенки, которые идеально подстраиваются под цвет натурального пигмента волос. Уверенность в результате существует благодаря запатентованной технологии ColorGrip, которая позволяет с легкостью добиться потрясающей четкости оттенка. С помощью технологии ColorGrip краситель великолепно закрашивает седину и надолго сохраняет стойкий цвет волос.';
$primenenie = 'Смешать краску с активатором в нужной пропорции. Нанести смесь на волосы "особое внимание уделяя корням" при помощи щетки или расчески и оставить на 20-45 минут. Затем тщательно смыть крем-краску теплой водой, высушить волосы полотенцем и воспользоваться бальзамом-кондиционером.';

foreach ($items as $item) {

	$product = \Krujeva\Data\Products::get($fromId);

	$product['oldid'] = $product['id'];

	unset($product['id']);

	$product['properties'] = [30 => $item, 31 => 69, 32 => $desc, 33 => $primenenie];

	$p = \Krujeva\Data\Products::insert($product);
}