<div class="middle-container">

	<div class="table admin-table-inn">
		<div class="tr">
			<div class="td">
				<div class="admin-t-inner">
					Меню
				</div>
			</div>
			<div class="td admin-left-block">

				<div class="link-create-new">
					<a href="/Admin/productsadd">Добавить блюдо</a>
				</div>

			</div>
		</div>

	</div>


	<div class="table admin-list">

		<?php $items = \Krujeva\Data\Products::select([
			'order' => 'categoryid, title'
		]); ?>

		<?php foreach($items as $item): ?>

			<div class="tr">
				<div class="td date-min-td">
					<?php echo \Krujeva\Categories::title((int)$item['categoryid']) ?>
				</div>
				<div class="td">
					<?php echo $item['title'] ?>
				</div>
				<div class="td admin-right-td-min">
					<div class="link-create-new">
						<a href="/Admin/productsadd?id=<?php echo $item['id'] ?>">Редактировать</a>
					</div>
				</div>
				<div class="td admin-right-td-min">
					<div class="link-create-new">
						<a href="javasript://" on-click="removeClick" data-id="<?php echo $item['id'] ?>">Удалить</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>


	</div>


</div>