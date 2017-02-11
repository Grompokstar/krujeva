<div class="middle-container">

	<div class="table admin-table-inn">
		<div class="tr">
			<div class="td">
				<div class="admin-t-inner">
					Обратная связь
				</div>
			</div>
			<div class="td admin-left-block">


				<div class="link-create-new">
					<a href="javascript://" on-click="downloadClick">Выгрузить в Excel</a>
				</div>

			</div>
		</div>

	</div>


	<div class="table admin-list">

		<?php $items = \Krujeva\Data\Feedback::select([
			'order' => 'datetime desc'
		]); ?>

		<?php foreach($items as $item): ?>
			<div class="tr">
				<div class="td date-min-td">
					<?php echo date('d.m.Y H:i', strtotime($item['datetime'])); ?>
				</div>
				<div class="td">
					<?php echo $item['name'] ?>
				</div>
				<div class="td">
					<?php echo $item['phone'] ?>
				</div>
				<div class="td">
					<?php echo $item['text'] ?>
				</div>
			</div>
		<?php endforeach; ?>


	</div>


</div>