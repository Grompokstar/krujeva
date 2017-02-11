<div class="middle-container">

	Административная панель сайта Ресторана & Lounge
	cafe «Кружева»

	<div class="table" style="margin: 60px 0">
		<div class="tr">
			<div class="td">
				<div class="form-settings form-edit-block" style="padding: 0">
					<div class="f-edit-admin-row">
						<label for="">Телефоны</label>
						<textarea style="height: 100px; width: 300px; padding: 15px"
								  name="value"><?php echo \Krujeva\Data\Settings::getByName('phones') ?></textarea>
					</div>

					<div class="login-btn btn-inline" data-name="phones" on-click="saveClick">Сохранить</div>
				</div>
			</div>
			<div class="td">
				<div class="form-settings form-edit-block" style="padding: 0">
					<div class="f-edit-admin-row">
						<label for="">Время работы</label>
						<textarea style="height: 100px; width: 300px; padding: 15px"
								  name="value"><?php echo \Krujeva\Data\Settings::getByName('time') ?></textarea>
					</div>

					<div class="login-btn btn-inline" data-name="time" on-click="saveClick">Сохранить</div>
				</div>
			</div>
		</div>
	</div>

</div>