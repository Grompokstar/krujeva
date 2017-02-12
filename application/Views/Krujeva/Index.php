<?php $items = \Krujeva\Data\Slider::select(['order' => 'id desc']); ?>

<?php if (count($items)): ?>
<div class="main-slider">

	<div class="middle-container">

		<div class="main-slider-cnt">

			<!-- Swiper -->
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($items as $item): ?>
						<div class="swiper-slide">
							<div class="table slide-inner-cnt">
								<div class="tr">
									<div class="td slide-img"
										 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>"></div>
									<div class="td slide-txt">

										<div>
											<div class="promo-slide-title">
												<?php echo $item['title'] ?>
											</div>

											<div class="promo-slide-desc">
												<?php echo $item['text'] ?>
											</div>

											<div class="promo-slide-price">
												<?php echo $item['price'] ?>
											</div>

											<div class="slide-desc-btn">
												подробнее →
												<a href="<?php echo $item['link'] ?>"></a>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Add Arrows -->
			<div class="swiper-button-prev prev-slide-btn"></div>
			<div class="swiper-button-next next-slide-btn"></div>

			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="about-restaurant">

	<div class="middle-container">

		<div class="abount-res-cnt">

			<!--row1-->
			<div class="table abount-rest-row">
				<div class="tr">
					<div class="td left-block">

						<div class="about-txt-block">
							<h1>О ресторане. Тест2</h1>

							<p>тест
								Говорят, что вкусы у каждого свои, и это правильно. Например, любимой едой
								каждый
								назовёт своё любимое блюдо. Кружева объединяет в своём меню предложения для всех
								и
								каждого. В заведении используются исключительно натуральные ингредиенты лучшего
								качества, что порадует даже видавшего виды клиента. Кружева — это прекрасная
								подача
								меню и дизайн интерьера, который идеально подобран, чтобы дать гостям обстановку
								спокойствия и уюта.
							</p>
						</div>

					</div>
					<div class="td right-block about1"></div>
				</div>
			</div>

			<!--row2-->
			<div class="table abount-rest-row">
				<div class="tr">
					<div class="td left-block about2"></div>
					<div class="td right-block">
						<div class="about-txt-block txt-block-2">
							<p>
								Благодаря этому проведенное в заведении время понравится всем, а внимательный
								персонал подскажет, на чем остановить ваше внимание и, в сочетании с галантным
								обслуживанием, принесет вам только позитивные эмоции.
							</p>
						</div>
					</div>
				</div>
			</div>

			<!--row3-->
			<div class="table abount-rest-row row-about3">
				<div class="tr">
					<div class="td left-block">

						<div class="about-txt-block">

							<p>
								Кружева — это вкуснейшие блюда со всей Европы, блюда русской кухни, знакомые с
								детства, суши и роллы из свежих продуктов, отличное место для семейных
								праздников, уютную обстановку, внимательный персонал, вкусный кофе, бизнес-ланч
								для деловых людей, сыграть с друзьями в настольные игры. В заведении вы сможете
								отведать популярные блюда японской кухни. Каждому посетителю заведение
								предлагает высокоскоростной бесплатный Wi-Fi, кальян с табаком различного вкуса,
								услуги поваров и официантов для мероприятий.
							</p>
						</div>

					</div>
					<div class="td right-block about3"></div>
				</div>
			</div>

		</div>

	</div>

</div><!-- .about-restaurant -->


<div class="main-photos">
<div class="middle-container">

<div class="title-photos">
	Фотографии
</div>


<div class="photos-cnt my-gallery">

<div class="table odd-hover-gallery">
	<div class="tr">

		<div class="td galery-item gl-photo" style="background-image: url('/public/img/gallery/min1.jpg')">
			<figure>
				<div href="/public/img/gallery/1.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min1.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min2.jpg')">
			<figure>
				<div href="/public/img/gallery/2.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min2.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min3.jpg')">
			<figure>
				<div href="/public/img/gallery/3.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min3.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min4.jpg')">
			<figure>
				<div href="/public/img/gallery/4.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min4.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min5.jpg')">
			<figure>
				<div href="/public/img/gallery/5.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min5.jpg"/>
				</div>
			</figure>
		</div>

	</div>
</div>


<div class="table even-hover-gallery">
	<div class="tr">

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min6.jpg')">
			<figure>
				<div href="/public/img/gallery/6.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min6.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min7.jpg')">
			<figure>
				<div href="/public/img/gallery/7.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min7.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min8.jpg')">
			<figure>
				<div href="/public/img/gallery/8.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min8.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min9.jpg')">
			<figure>
				<div href="/public/img/gallery/9.jpg"
					 data-size="1400x935">
					<img style="visibility: hidden" src="/public/img/gallery/min9.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min10.jpg')">
			<figure>
				<div href="/public/img/gallery/10.jpg"
					 data-size="1400x1050">
					<img style="visibility: hidden" src="/public/img/gallery/min10.jpg"/>
				</div>
			</figure>
		</div>

	</div>
</div>


<div class="table odd-hover-gallery">
	<div class="tr">

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min11.jpg')">
			<figure>
				<div href="/public/img/gallery/11.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min11.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min12.jpg')">
			<figure>
				<div href="/public/img/gallery/12.jpg"
					 data-size="1400x962">
					<img style="visibility: hidden" src="/public/img/gallery/min12.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min13.jpg')">
			<figure>
				<div href="/public/img/gallery/13.jpg"
					 data-size="1400x1037">
					<img style="visibility: hidden" src="/public/img/gallery/min13.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min14.jpg')">
			<figure>
				<div href="/public/img/gallery/14.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min14.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min15.jpg')">
			<figure>
				<div href="/public/img/gallery/15.jpg"
					 data-size="1400x1012">
					<img style="visibility: hidden" src="/public/img/gallery/min15.jpg"/>
				</div>
			</figure>
		</div>

	</div>
</div>

<div class="table even-hover-gallery">
	<div class="tr">

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min16.jpg')">
			<figure>
				<div href="/public/img/gallery/16.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min16.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min17.jpg')">
			<figure>
				<div href="/public/img/gallery/17.jpg"
					 data-size="1400x930">
					<img style="visibility: hidden" src="/public/img/gallery/min17.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min18.jpg')">
			<figure>
				<div href="/public/img/gallery/18.jpg"
					 data-size="1400x942">
					<img style="visibility: hidden" src="/public/img/gallery/min18.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min19.jpg')">
			<figure>
				<div href="/public/img/gallery/19.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min19.jpg"/>
				</div>
			</figure>
		</div>

		<div class="td galery-item gl-photo"
			 style="background-image: url('/public/img/gallery/min20.jpg')">
			<figure>
				<div href="/public/img/gallery/20.jpg"
					 data-size="1400x933">
					<img style="visibility: hidden" src="/public/img/gallery/min20.jpg"/>
				</div>
			</figure>
		</div>

	</div>
</div>

</div>

</div>
</div> <!-- .main-photos -->

<!--
<div class="main-reviews">
	<div class="middle-container">

		<div class="title-photos">
			Отзывы
		</div>

		<div class="review">
			<div class="photo-review"></div>
			<div class="text-review">
				<div class="name-review">Анашкин Андрей</div>
				<div class="desc-review">
					Говорят, что вкусы у каждого свои, и это правильно. Например, любимой едой каждый назовёт
					своё любимое блюдо. Кружева объединяет в своём меню предложения для всех и каждого. В
					заведении используются исключительно натуральные ингредиенты лучшего качества, что порадует
					даже видавшего виды клиента.
				</div>
			</div>
			<div style="clear: both"></div>
		</div>

		<div class="review review-right">
			<div class="photo-review"></div>
			<div class="text-review">
				<div class="name-review">Анашкин Андрей</div>
				<div class="desc-review">
					Говорят, что вкусы у каждого свои, и это правильно. Например, любимой едой каждый назовёт
					своё любимое блюдо. Кружева объединяет в своём меню предложения для всех и каждого. В
					заведении используются исключительно натуральные ингредиенты лучшего качества, что порадует
					даже видавшего виды клиента.
				</div>
			</div>
			<div style="clear: both"></div>
		</div>

		<div class="review">
			<div class="photo-review"></div>
			<div class="text-review">
				<div class="name-review">Анашкин Андрей</div>
				<div class="desc-review">
					Говорят, что вкусы у каждого свои, и это правильно. Например, любимой едой каждый назовёт
					своё любимое блюдо. Кружева объединяет в своём меню предложения для всех и каждого. В
					заведении используются исключительно натуральные ингредиенты лучшего качества, что порадует
					даже видавшего виды клиента.
				</div>
			</div>
			<div style="clear: both"></div>
		</div>

		<div class="btn-skeleton all-reviews-btn">
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




