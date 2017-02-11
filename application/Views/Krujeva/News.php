<div class="main-menu-page news-page">

	<div class="middle-container">

		<?php $items = \Krujeva\Data\News::select(['order' => 'date desc']); ?>

		<?php
			$limit1block =2;
			$limit2block=3;
			$currentIndex = 0;
			$currentBlock = 1; //or 2
			$initializedBlock = false;
			$bigImageBlock1 = true;

			foreach ($items as $item): ?>

				<?php //verify is limited
					 if ($currentBlock == 1 && $currentIndex >= $limit1block) {
						 $initializedBlock = false;
						 $currentBlock = 2;
						 $currentIndex = 0;
						 $bigImageBlock1 = !$bigImageBlock1;

						 //finish 1 block (end div)
						 echo '</div></div>';


					 } else if ($currentBlock == 2 && $currentIndex >= $limit2block) {
						 $initializedBlock = false;
						 $currentBlock = 1;
						 $currentIndex = 0;

						 //finish 2 block
						 echo '</div></div>';
					 }
				?>

				<?php if ($currentBlock == 1) { ?>

					<?php if (!$initializedBlock) { $initializedBlock = true; ?>
						<div class="news-row table">
							<div class="tr">
					<?php } ?>

					<div class="td <?php echo $currentIndex == 1? 'news-cnt-right' : '' ?>">
						<div class="<?php echo $bigImageBlock1 ? 'new-cnt-big' : 'new-cnt-small' ?>">

							<?php $bigImageBlock1 = !$bigImageBlock1; ?>

							<div class="new-img-preview"
								 style="<?php echo $item['relativepath']? 'background-image: url(\''. $item['relativepath'] . $item['name'] .'\')' : ''  ?>">
								<a href="/news/view?id=<?php echo $item['id'] ?>"></a>
							</div>
							<div class="p-cnt-txt-block">
								<div class="p-data-grey">
									<?php echo \Utils::localDate($item['date'], true) ?>
								</div>
								<div class="p-data-title">
									<a href="/news/view?id=<?php echo $item['id'] ?>">
										<?php echo ($item['title']) ?>
									</a>
								</div>
							</div>
						</div>
					</div>

					<?php $currentIndex++; ?>

				<?php } else { ?>

					<?php if (!$initializedBlock) {$initializedBlock = true; ?>
						<div class="news-row table">
							<div class="tr">
					<?php } ?>

					<div class="td td-fix">
						<div class="p-container">
							<div class="p-cnt-img-bg"
								 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
								<a href="/news/view?id=<?php echo $item['id'] ?>"></a>
							</div>
							<div class="p-cnt-txt-block">
								<div class="p-data-grey">
									<?php echo \Utils::localDate($item['date'], true) ?>
								</div>
								<div class="p-data-title">
									<a href="/news/view?id=<?php echo $item['id'] ?>">
										<?php echo($item['title']) ?>
									</a>
								</div>
							</div>
						</div>
					</div>

					<?php $currentIndex++; ?>

				<?php } ?>


		<?php endforeach; ?>

		<?php
			if ($currentBlock == 1) {
				//finish 1 block (end div)
				echo '</div></div>';
			} else {
				//finish 2 block
				echo '</div></div>';
			}
		?>


	</div>
</div>