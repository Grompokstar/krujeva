<div class="mobile-events-cnt">
	<div class="mobile-title">Новости</div>

	<?php $items = \Krujeva\Data\News::select(['order' => 'date desc']); ?>

	<?php foreach ($items as $item): ?>

		<div class="mobile-pline-row">
			<div class="mobile-p-container">
				<div class="mobile-p-cnt-img-bg" style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
					<a href="/news/view?id=<?php echo $item['id'] ?>"></a>
				</div>
				<div class="mobile-p-cnt-txt-block">
					<div class="mobile-p-data-grey">
						<?php echo \Utils::localDate($item['date'], true) ?>
					</div>
					<div class="mobile-p-data-title">
						<a href="/news/view?id=1">
							<?php echo($item['title']) ?>
						</a>
					</div>
				</div>
			</div>
		</div>

	<?php endforeach; ?>

</div>