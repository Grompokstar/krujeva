Module.define(
	"Enum",

	function () {
		NS("Security");

		Security.AccessMode = Static(Enum, {
			Read: 1,
			Insert: 2,
			Update: 3,
			Remove: 4,
			Execute: 5,
			View: 6,

			title: function(value) {

				switch (value) {
					case this.Read:
						return "Чтение";
						break;
					case this.Insert:
						return "Вставка";
						break;
					case this.Update:
						return "Обновление";
						break;
					case this.Remove:
						return "Удаление";
						break;
					case this.Execute:
						return "Исполнение";
						break;
					case this.View:
						return "Просмотр";
						break;
				}

				return this.Parent(value);
			}
		});
	}
);
