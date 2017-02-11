<div class="mobile-contacts-form">

	<div class="m-cnt-text">
		<div class="m-cnt-title">
			Контакты
		</div>

		<div class="m-cnt-row">
			<?php $res = \Krujeva\Data\Settings::getByName('phones');

			$items = explode("\n", $res);

			foreach ($items as $item):?>
				<div style="margin-bottom: 1px">
					<?php echo $item ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="m-cnt-row">
			г. Казань, ул. Гвардейская, 15, 1 этаж
		</div>

		<div class="m-cnt-row">
			Время работы <br>

			<?php $res = \Krujeva\Data\Settings::getByName('time');

			$items = explode("\n", $res);

			foreach ($items as $item):?>
				<div style="margin-bottom: 1px">
					<?php echo $item ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>


	<div class="m-contacts-form" name="form">
		<div class="m-cnt-title">
			Обратная связь
		</div>

		<div class="m-contacts-form-row">
			<label for="">имя</label>
			<input type="text" name="name" placeholder="Введите ваше имя"/>
		</div>

		<div class="m-contacts-form-row">
			<label for="">Телефон</label>
			<input type="text" name="phone" placeholder="+7" value="+7"/>
		</div>

		<div class="m-contacts-form-row">
			<label for="">Сообщение</label>
			<textarea name="text" placeholder="Введите ваше сообщение"></textarea>
		</div>

		<div class="m-contacts-form-row">

			<div class="contacts-btn" on-click="sendClick">Отправить</div>

			<div class="contacts-desc" style="margin-top: 15px">
				Нажимая «Отправить», вы соглашаетесь <br>
				с обработкой ваших персональных данных
			</div>

		</div>
	</div>

</div>