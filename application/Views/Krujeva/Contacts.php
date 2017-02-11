<div class="contacts">
	<div class="middle-container">

		<div class="table table-contacts-main">
			<div class="tr">
				<div class="td td-left-main">
					<div class="contacts-left">

						<div class="contacts-title">Контакты</div>

						<div class="cnt-row">

							<?php $res = \Krujeva\Data\Settings::getByName('phones');

							$items = explode("\n", $res);

							foreach ($items as $item):?>
								<div style="margin-bottom: 1px">
									<?php echo $item ?>
								</div>
							<?php endforeach; ?>

						</div>

						<div class="cnt-row">
							г. Казань, ул. Гвардейская, 15, 1 этаж
						</div>

						<div class="cnt-row">
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
				</div>
				<div class="td td-right-main">
					<div class="contacts-right" name="form">
						<div class="contacts-title">Обратная связь</div>

						<div class="contacts-form-row">
							<label for="">имя</label>
							<input type="text" name="name" placeholder="Введите ваше имя"/>
						</div>

						<div class="contacts-form-row">
							<label for="">Телефон</label>
							<input type="text" name="phone" placeholder="+7" value="+7"/>
						</div>

						<div class="contacts-form-row">
							<label for="">Сообщение</label>
							<textarea name="text" placeholder="Введите ваше сообщение"></textarea>
						</div>

						<div class="contacts-form-row">

							<div class="table contacts-form-table">
								<div class="tr">
									<div class="td td-left">
										<div class="contacts-desc">
											Нажимая «Отправить», вы соглашаетесь <br>
											с обработкой ваших персональных данных
										</div>
									</div>
									<div class="td td-right">
										<div class="contacts-btn" on-click="sendClick">Отправить</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
