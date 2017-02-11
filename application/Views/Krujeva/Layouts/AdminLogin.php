<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta charset='utf-8'>

	<title>Ресторан Кружева</title>

	<meta name="viewport" content="width=device-width, initial-scale=0.5, maximum-scale=1"/>

	<?php echo \Compressor\Statics::css([
		'/public/css/utils/photoswipe.css',
		'/public/css/utils/swiper.min.css',
		'/public/css/utils/default-skin/default-skin.css',
		'/public/css/krujeva/font.css',
		'/public/css/krujeva/main.css'
	]); ?>
</head>
<body>
	<div class='main-container' id='main-container'>

		<div class="header middle-container">
			<div class="table">
				<div class="tr">
					<div class="td">

						<div class="header-info">

						</div>

					</div>
					<div class="td header-logo-cnt">

						<div class="logo">
							<div class="logo-desc">
								Ресторан & Lounge<br>cafe «Кружева»
							</div>
						</div>

					</div>
					<div class="td right-info">

						<div class="header-info">

						</div>

					</div>
				</div>
			</div>
		</div>
		<?php echo @$content?>
	</div>

	<div class="dev-wp-fkf"></div>

	<?php echo \Compressor\Statics::js([
		'js/libs/ejs_production.js',
		'js/libs/jquery-2.1.4.min.js',
		'js/libs/swiper.jquery.min.js',
		'js/libs/photoswipe/main.js',
		'js/libs/photoswipe/ui.js',

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

		['includeEJS', '/public/templates/',],

		['includeJS', '/public/js/App/Application.js'],

	]); ?>

	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
</body>
</html>