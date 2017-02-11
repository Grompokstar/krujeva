Module.define(
	"Enum",

	function () {
		NS("KrujevaDict");

		KrujevaDict.PropertyDataType = Static(Enum, {
			String: 1,
			Int: 2,
			Float: 3,
			Text: 4,
			CheckBox: 5,

			title: function (value) {
				switch (value) {
					case this.String:
						return "Строка";
					case this.Int:
						return "Целое";
					case this.Float:
						return "С плавающей точкой";
					case this.Text:
						return "Много текста";
					case this.CheckBox:
						return "Галка";
				}

				return this.Parent(value);
			}
		});
	}
);