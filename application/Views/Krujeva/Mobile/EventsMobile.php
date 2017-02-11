<?php $items = \Krujeva\Data\SliderEvents::select(['order' => 'id desc']); ?>

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


<?php $items = \Krujeva\Data\Events::select(['where' => 'date >= $1', 'data' => [date('Y-m-d')]]); ?>


<?php if (count($items)): ?>

	<div class="mobile-events-cnt">
		<div class="mobile-title">Мероприятия</div>

		<?php foreach ($items as $item): ?>
		<div class="mobile-pline-row">
			<div class="mobile-p-container">
				<div class="mobile-p-cnt-img-bg" style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
					<a href="/events/view?id=<?php echo $item['id'] ?>"></a>
				</div>
				<div class="mobile-p-cnt-txt-block">
					<div class="mobile-p-data-grey">
						<?php echo \Utils::localDate($item['date'], true) ?>
					</div>
					<div class="mobile-p-data-title">
						<a href="/events/view?id=<?php echo $item['id'] ?>">
						<?php echo($item['title']) ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>

<?php $items = \Krujeva\Data\Events::select(['where' => 'date < $1', 'data' => [date('Y-m-d')]]); ?>

<?php if (count($items)): ?>

	<div class="mobile-events-cnt">
		<div class="mobile-title">Прошедшие Мероприятия</div>

		<?php foreach ($items as $item): ?>
			<div class="mobile-pline-row past-event">
				<div class="mobile-p-container">
					<div class="mobile-p-cnt-img-bg"
						 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
						<a href="/events/view?id=<?php echo $item['id'] ?>"></a>
					</div>
					<div class="mobile-p-cnt-txt-block">
						<div class="mobile-p-data-grey">
							<?php echo \Utils::localDate($item['date'], true) ?>
						</div>
						<div class="mobile-p-data-title">
							<a href="/events/view?id=<?php echo $item['id'] ?>">
								<?php echo($item['title']) ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>