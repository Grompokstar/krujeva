<?php $items = \Krujeva\Data\SliderEvents::select(['order' => 'id desc']); ?>

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


<div class="main-menu-page events-page">
	<div class="middle-container">

		<?php $items = \Krujeva\Data\Events::select(['where' => 'date >= $1', 'data' => [date('Y-m-d')]]); ?>


		<?php if (count($items)): $items = \Krujeva\Data\Events::split3($items);  ?>

			<div class="contacts-title inner-title">
				Мероприятия
			</div>

			<?php foreach ($items as $itemTable): ?>
				<div class="pline-row table" style="margin-bottom: 30px">
					<div class="tr">

						<?php foreach ($itemTable as $index => $item): ?>
							<div class="td <?php echo $index ==1 ? 'td-center' : 'td-fix'  ?> ">
								<div class="p-container">
									<div class="p-cnt-img-bg"
										 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
											<a href="/events/view?id=<?php echo $item['id'] ?>"></a>
										</div>
									<div class="p-cnt-txt-block">
										<div class="p-data-grey">
											<?php echo \Utils::localDate($item['date'], true) ?>
										</div>
										<div class="p-data-title">
											<a href="/events/view?id=<?php echo $item['id'] ?>">
												<?php echo($item['title']) ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>



		<?php $items = \Krujeva\Data\Events::select(['where' => 'date < $1', 'data' => [date('Y-m-d')]]); ?>


		<?php if (count($items)): $items = \Krujeva\Data\Events::split3($items); ?>

			<div class="contacts-title inner-title">
				Прошедшие Мероприятия
			</div>

			<?php foreach ($items as $itemTable): ?>
				<div class="pline-row table" style="margin-bottom: 30px">
					<div class="tr">

						<?php foreach ($itemTable as $index => $item): ?>
							<div class="td <?php echo $index == 1 ? 'td-center' : 'td-fix' ?> ">
								<div class="p-container past-event">
									<div class="p-cnt-img-bg"
										 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
										<a href="/events/view?id=<?php echo $item['id'] ?>"></a>
									</div>
									<div class="p-cnt-txt-block">
										<div class="p-data-grey">
											<?php echo \Utils::localDate($item['date'], true) ?>
										</div>
										<div class="p-data-title">
											<a href="/events/view?id=<?php echo $item['id'] ?>">
												<?php echo($item['title']) ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>



	</div>
</div>