<div class="main-menu-page news-page">

	<div class="middle-container">

		<div class="back-news">
			<a href="/events">← назад к мероприятиям</a>
		</div>

		<?php if (isset($_GET['id']) && $item = \Krujeva\Data\Events::get($_GET['id'])): ?>

			<div class="new-img-full" style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
				<div class="data-img-full-cnt">
					<div class="title-new">
						<?php echo($item['title']) ?>
					</div>
					<div class="date-new">
						<?php echo \Utils::localDate($item['date'], true) ?>
					</div>
				</div>
			</div>

			<div class="new-text">
				<?php echo($item['text']) ?>
			</div>

		<?php endif; ?>

	</div>

</div>