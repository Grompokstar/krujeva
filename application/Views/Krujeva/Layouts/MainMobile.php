<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta charset='utf-8'>

	<title>Ресторан Кружева</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">

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

		<div class="mobile-header mobile-padding">
			<div class="table">
				<div class="tr">
					<div class="td">
						<div class="m-header-info">
							<div class="m-info-h-title">
								Часы работы
							</div>
							<?php $res = \Krujeva\Data\Settings::getByName('time');

							$items = explode("\n", $res);

							foreach ($items as $item):?>
								<div class="m-info-h-desc">
									<?php echo $item ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="td m-h-right-block">
						<div class="m-header-info">
							<div class="m-info-h-title">
								ул. Гвардейская, 15
							</div>
							<?php $res = \Krujeva\Data\Settings::getByName('phones');

							$items = explode("\n", $res);

							foreach ($items as $item):?>
								<div class="m-info-h-desc">
									<?php echo $item ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mobile-header-logo-cnt mobile-padding">
			<div class="logo">
				<div class="logo-desc">
					Ресторан & Lounge<br>cafe «Кружева»
				</div>
			</div>
		</div>

		<div class="mobile-menu mobile-padding">
			<ul class="mobile-ul-menu">
				<?php foreach(\Krujeva\MainMenu::menu() as $item) {?>
					<li class="<?php echo $item['active'] ?>">
						<a href="<?php echo $item['url'] ?>"><?php echo $item['title'] ?></a>
					</li>
				<?php } ?>
			</ul>
		</div>

		<?php echo @$content?>

		<div class="mobile-map" id="map"></div>
		<div class="mobile-address-footer">
			г. Казань, ул. Гвардейская, 15, 1 этаж
		</div>

		<div class="mobile-header mobile-padding" style="background: #fff">
			<div class="table">
				<div class="tr">
					<div class="td">
						<div class="m-header-info">
							<div class="m-info-h-title">
								Часы работы
							</div>
							<?php $res = \Krujeva\Data\Settings::getByName('time');

							$items = explode("\n", $res);

							foreach ($items as $item):?>
								<div class="m-info-h-desc">
									<?php echo $item ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="td m-h-right-block">
						<div class="m-header-info">
							<div class="m-info-h-title">
								&nbsp;
							</div>
							<?php $res = \Krujeva\Data\Settings::getByName('phones');

							$items = explode("\n", $res);

							foreach ($items as $item):?>
								<div class="m-info-h-desc">
									<?php echo $item ?>
								</div>
							<?php endforeach; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="dev-wp-fkf"></div>


	<?php echo \Compressor\Statics::js([
		'js/libs/ejs_production.js',
		'js/libs/jquery-2.1.4.min.js',
		'js/libs/swiper.jquery.min.js',
		'js/libs/photoswipe/main.js',
		'js/libs/photoswipe/ui.js',
		'js/libs/jquery.mask.js',

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
	<!-- Yandex.Metrika informer -->
	<a href="https://metrika.yandex.ru/stat/?id=43756824&amp;from=informer"
	   target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/43756824/3_0_FFFFFFFF_EFEFEFFF_0_pageviews"
										   style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="43756824" data-lang="ru" ><a>
			<!-- /Yandex.Metrika informer -->

			<!-- Yandex.Metrika counter -->
			<script type="text/javascript">
				(function (d, w, c) {
					(w[c] = w[c] || []).push(function() {
						try {
							w.yaCounter43756824 = new Ya.Metrika({
								id:43756824,
								clickmap:true,
								trackLinks:true,
								accurateTrackBounce:true,
								webvisor:true,
								ecommerce:"dataLayer"
							});
						} catch(e) { }
					});

					var n = d.getElementsByTagName("script")[0],
						s = d.createElement("script"),
						f = function () { n.parentNode.insertBefore(s, n); };
					s.type = "text/javascript";
					s.async = true;
					s.src = "https://mc.yandex.ru/metrika/watch.js";

					if (w.opera == "[object Opera]") {
						d.addEventListener("DOMContentLoaded", f, false);
					} else { f(); }
				})(document, window, "yandex_metrika_callbacks");
			</script>
			<noscript><div><img src="https://mc.yandex.ru/watch/43756824" style="position:absolute; left:-9999px;" alt="" ><div></noscript>
			<!-- /Yandex.Metrika counter -->
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<script type="text/javascript">var isMobilePage = true;</script>
</body>
</html>