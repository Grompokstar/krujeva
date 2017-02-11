<div class="">
	<div class="middle-container menu-inner-page">

		<div class="relative">
			<div class="back-news absolute-back" style="margin-bottom: 0">
				<a href="/menu">← назад к меню</a>
			</div>

			<div class="contacts-title inner-title" style="margin-top: 0">
				<?php echo isset($_GET['id']) ? \Krujeva\Categories::title((int)$_GET['id']) : '' ?>
			</div>
		</div>

		<?php $items = isset($_GET['id']) ?  \Krujeva\Data\Products::select(['order' => 'id', 'where' => 'categoryid = $1', 'data' => [$_GET['id']]]) : []; ?>

		<?php if (count($items)): $items = \Krujeva\Data\Events::split3($items, true);  ?>

			<?php foreach ($items as $itemTable): ?>
				<div class="pline-row table" style="margin-bottom: 30px">
					<div class="tr">

						<?php foreach ($itemTable as $index => $item): ?>
							<div class="td <?php echo $index ==1 ? 'td-center' : 'td-fix'  ?> ">

								<?php if (!isset($item['empty'])) { ?>
									<div class="p-container menu-item-cnt">
										<div class="p-cnt-img-bg"
											 style="<?php echo $item['relativepath'] ? 'background-image: url(\'' . $item['relativepath'] . $item['name'] . '\')' : '' ?>">
											</div>
										<div class="p-cnt-txt-block product-cnt-txt relative">
											<div class="p-data-title">
												<?php echo($item['title']) ?>
											</div>
											<div class="p-data-grey">
												<?php echo($item['text']) ?>
											</div>

											<div class="abs-prices-menu">
											<?php if ($item['price'] && $prices = \JSON::parse($item['price'])) {

												foreach ($prices as $price) { ?>

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
												<?php }

											}  ?>
											</div>

										</div>
									</div>
								<?php } ?>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>


</div>