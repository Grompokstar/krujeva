<div class=" mobile-new-view" style="padding: 15px; text-align: center">

	<div class="back-news">
		<a href="/menu">← назад к меню</a>
	</div>
</div>

<div class="mobile-events-cnt m-menu-cnt">
	<div class="mobile-title"><?php echo isset($_GET['id']) ? \Krujeva\Categories::title((int)$_GET['id']) : '' ?></div>

		<?php $items = isset($_GET['id']) ? \Krujeva\Data\Products::select(['order' => 'id', 'where' => 'categoryid = $1', 'data' => [$_GET['id']]]) : []; ?>

		<?php if (count($items)): ?>

				<?php foreach ($items as $item): ?>
				<div class="mobile-pline-row">
					<div class="mobile-p-container m-product-item">
						<div class="mobile-p-cnt-img-bg" style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
						</div>
						<div class="mobile-p-cnt-txt-block relative">
							<div class="mobile-p-data-title">
								<?php echo($item['title']) ?>
							</div>
							<div class="mobile-p-data-grey">
								<?php echo $item['text']; ?>
							</div>

							<div class="abs-prices-menu-mobile">
								<?php if ($item['price'] && $prices = \JSON::parse($item['price'])) {

									foreach ($prices as $price) {
										?>

										<div class="p-data-title abs-price">

											<div class="table">
												<div class="tr">
													<div class="td volume-menuitem">
														<?php echo $price['volume']; ?>
													</div>
													<div class="td price-menuitem">
														<?php echo $price['price']; ?> Руб.
													</div>
												</div>
											</div>


										</div>
									<?php
									}
								}  ?>
							</div>


						</div>
					</div>
				</div>
				<?php endforeach; ?>


		<?php endif; ?>

</div>
