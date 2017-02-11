<div class="middle-container">

	<?php $item = null;

		if (isset($_GET['id'])) {
			$item = \Krujeva\Data\Slider::get($_GET['id']);
		}
	?>

	<div class="table admin-table-inn">
		<div class="tr">
			<div class="td">
				<div class="admin-t-inner">
					<?php echo $item ? 'Редактирование слайда' : 'Добавление слайда' ?>
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
			<label for="">Цена</label>
			<input type="text" name="price" value="<?php echo isset($item['price']) ? \Utils::eq($item['price']) : '' ?>"/>
		</div>

		<div class="f-edit-admin-row">
			<label for="">Ссылка</label>
			<input type="text" name="link" value="<?php echo isset($item['link']) ? $item['link'] : '' ?>"/>
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
			<label for="">Описание</label>
			<textarea style="width: 1100px; height: 200px" name="text"><?php echo isset($item['text']) ? \Utils::eq($item['text']) : '' ?></textarea>
		</div>

		<div class="login-btn btn-inline" on-click="saveClick">Сохранить слайд</div>
	</div>


</div>