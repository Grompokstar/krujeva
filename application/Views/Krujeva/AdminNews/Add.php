<div class="middle-container">

	<?php $item = null;

		if (isset($_GET['id'])) {
			$item = \Krujeva\Data\News::get($_GET['id']);
		}
	?>

	<div class="table admin-table-inn">
		<div class="tr">
			<div class="td">
				<div class="admin-t-inner">
					<?php echo $item ? 'Редактирование новости' : 'Добавление новости' ?>
				</div>
			</div>
			<div class="td admin-left-block"></div>
		</div>
	</div>

	<div class="form-edit-block" name="form">

		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : '' ?>"/>

		<div class="f-edit-admin-row">
			<label for="">Заголовок</label>
			<input type="text" name="title" value="<?php echo isset($item['title']) ? \Utils::eq($item['title']) : '' ?>"/>
		</div>

		<div class="f-edit-admin-row">
			<label for="">Дата создания</label>
			<input type="text" name="date" value="<?php echo isset($item['date']) ? date('d.m.Y', strtotime($item['date'])) : '' ?>"/>
		</div>

		<div class="photo-product" name="photo-product-preview">
			<?php if (isset($item['relativepath'])): ?>
				<img src="<?php echo $item['relativepath']. $item['name'] ?>" alt=""/>
			<?php endif; ?>
		</div>

		<div class="f-edit-admin-row">
			<label for="">Изображение (preview)</label>
			<div class="login-btn relative btn-inline upload-btn">
				Загрузить изображение
				<input class="file-loader-todo" type="file" name="photo-file" on-change="onFileChange"/>
			</div>
		</div>

		<div class="f-edit-admin-row">
			<label for="">Текст новости</label>
			<textarea style="width: 1100px" class="suitup-textarea" name="text">
				<?php echo isset($item['text']) ? \Utils::eq($item['text']) : '' ?>
			</textarea>
		</div>

		<div class="login-btn btn-inline" on-click="saveClick">Сохранить новость</div>
	</div>


</div>