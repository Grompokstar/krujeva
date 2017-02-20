<?php $items = \Krujeva\Data\Slider::select(['order' => 'id desc']); ?>

<?php if (count($items)): ?>
<div class="mobile-slider">

	<!-- Swiper -->
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php foreach ($items as $item): ?>

			<div class="swiper-slide">
				<div class="m-inner-slide">
					<div class="mobile-slider-photo"
						 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>"></div>
					<div class="slider-padding">
						<div class="m-slider-title">
							<?php echo $item['title'] ?>
						</div>
						<div class="m-slider-desc">
							<?php echo $item['text'] ?>
						</div>
						<div class="m-slider-btn">
							подробнее →
							<a href="<?php echo $item['link'] ?>"></a>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>


	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
</div>
<?php endif; ?>

<div class="mobile-about-company">

	<div class="mobile-title">О ресторане</div>

	<div class="m-about-text">
		О вкусах не спорят, и это правильно, ведь у каждого есть своё любимое блюдо.

		В меню нашего ресторан вы найдете предложения для всех и каждого. Мы используем исключительно натуральные
		ингредиенты лучшего качества, что удивит даже видавшего виды гостя.
	</div>

	<div class="m-about-img"></div>

	<div class="m-about-text">
		Кружева — это интересная подача меню и дизайн интерьера, который идеально подобран,
		чтобы дать гостям обстановку спокойствия и домашнего уюта.

	</div>

	<div class="m-about-img"></div>

	<div class="m-about-text">
		Благодаря этому проведенное в заведении время понравится всем, а внимательный персонал подскажет,
		на чем остановить ваше внимание и, в сочетании с галантным обслуживанием, принесет вам только позитивные эмоции.

		Романтический ужин или деловой обед, дружеские посиделки или семейный праздник, банкет,
		свадьба - любое событие оставит отличное впечатление!
	</div>

	<div class="m-about-img-2"></div>
</div>

<div class="mobile-gallery">
	<div class="mobile-title">Фотографии</div>

	<div class="photos-cnt m-photos-cnt my-gallery">

		<div class="table odd-hover-gallery">
			<div class="tr">

				<div class="td galery-item gl-photo" style="background-image: url('/public/img/gallery/min1.jpg')">
					<figure>
						<div href="/public/img/gallery/1.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min1.jpg"/>
						</div>
					</figure>
				</div>

				<div class="td galery-item gl-photo"
					 style="background-image: url('/public/img/gallery/min2.jpg')">
					<figure>
						<div href="/public/img/gallery/2.jpg"
							 data-size="1400x933">
							<img src="/public/img/gallery/min2.jpg"/>
						</div>
					</figure>
				</div>
			</div>
		</div>

		<div class="table even-hover-gallery">
			<div class="tr">

				<div class="td galery-item gl-photo" style="background-image: url('/public/img/gallery/min3.jpg')">
					<figure>
						<div href="/public/img/gallery/3.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min3.jpg"/>
						</div>
					</figure>
				</div>

				<div class="td galery-item gl-photo"
					 style="background-image: url('/public/img/gallery/min4.jpg')">
					<figure>
						<div href="/public/img/gallery/4.jpg"
							 data-size="1400x933">
							<img src="/public/img/gallery/min4.jpg"/>
						</div>
					</figure>
				</div>
			</div>
		</div>

		<div class="table odd-hover-gallery">
			<div class="tr">

				<div class="td galery-item gl-photo" style="background-image: url('/public/img/gallery/min5.jpg')">
					<figure>
						<div href="/public/img/gallery/5.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min5.jpg"/>
						</div>
					</figure>
				</div>

				<div class="td galery-item gl-photo"
					 style="background-image: url('/public/img/gallery/min6.jpg')">
					<figure>
						<div href="/public/img/gallery/6.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min6.jpg"/>
						</div>
					</figure>
				</div>
			</div>
		</div>

		<div class="table even-hover-gallery">
			<div class="tr">

				<div class="td galery-item gl-photo" style="background-image: url('/public/img/gallery/min7.jpg')">
					<figure>
						<div href="/public/img/gallery/7.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min7.jpg"/>
						</div>
					</figure>
				</div>

				<div class="td galery-item gl-photo"
					 style="background-image: url('/public/img/gallery/min8.jpg')">
					<figure>
						<div href="/public/img/gallery/8.jpg"
							 data-size="1400x935">
							<img src="/public/img/gallery/min8.jpg"/>
						</div>
					</figure>
				</div>
			</div>
		</div>

	</div>
</div>


<!--
<div class="mobile-rewiew">
	<div class="mobile-title">Отзывы</div>

	<div class="mobile-rewiew-block">
		<div class="mobile-photo-rewiew"></div>

		<div class="mobile-text-rewiew">

			<div class="mobile-title-rewiew">
				Анашкин Андрей
			</div>

			Говорят, что вкусы у каждого свои, и это правильно. Например, любимой едой каждый назовёт своё любимое
			блюдо. Кружева объединяет в своём меню предложения для всех и каждого. В заведении используются
			исключительно натуральные ингредиенты лучшего качества, что порадует даже видавшего виды клиента.
		</div>
	</div>

	<div class="m-rewiew-btn-cnt">
		<div class="m-slider-btn">
			показать еще
		</div>
	</div>
</div>
-->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

	<!-- Background of PhotoSwipe.
		 It's a separate element as animating opacity is faster than rgba(). -->
	<div class="pswp__bg"></div>

	<!-- Slides wrapper with overflow:hidden. -->
	<div class="pswp__scroll-wrap">

		<!-- Container that holds slides.
			PhotoSwipe keeps only 3 of them in the DOM to save memory.
			Don't modify these 3 pswp__item elements, data is added later on. -->
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>

		<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
		<div class="pswp__ui pswp__ui--hidden">

			<div class="pswp__top-bar">

				<!--  Controls are self-explanatory. Order can be changed. -->

				<div class="pswp__counter"></div>

				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

				<button class="pswp__button pswp__button--share" title="Share"></button>

				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

				<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

				<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
				<!-- element will get class pswp__preloader--active when preloader is running -->
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>

			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
			</button>

			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
			</button>

			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>

		</div>

	</div>

</div>