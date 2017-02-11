<div class="middle-container">

	<div class="table admin-table-inn">
		<div class="tr">
			<div class="td">
				<div class="admin-t-inner">
					Новости
				</div>
			</div>
			<div class="td admin-left-block">

				<div class="link-create-new">
					<a href="/Admin/newsadd">Добавить новость</a>
				</div>

			</div>
		</div>

	</div>


	<div class="table admin-list">

		<?php $items = \Krujeva\Data\News::select([
			'order' => 'date desc'
		]); ?>

		<?php foreach($items as $item): ?>
			<div class="tr">
				<div class="td date-min-td">
					<?php echo date('d.m.Y', strtotime($item['date'])); ?>
				</div>
				<div class="td">
					<?php echo $item['title'] ?>
				</div>
				<div class="td admin-right-td-min">
					<div class="link-create-new">
						<a href="/Admin/newsadd?id=<?php echo $item['id'] ?>">Редактировать</a>
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