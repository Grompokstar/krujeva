<div class="mobile-events-cnt mobile-new-view">

	<div class="back-news">
		<a href="/news">← назад к новостям</a>
	</div>
</div>

<?php if (isset($_GET['id']) && $item = \Krujeva\Data\News::get($_GET['id'])): ?>

	<div class="new-img-full mobile-img-full" style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
		<div class="data-img-full-cnt">
			<div class="title-new">
				<?php echo($item['title']) ?>
			</div>
			<div class="date-new">
				<?php echo \Utils::localDate($item['date'], true) ?>
			</div>
		</div>
	</div>

	<div class="mobile-events-cnt mobile-new-view">
		<div class="new-text">
			<?php echo($item['text']) ?>
		</div>

	</div>

<?php endif; ?>

