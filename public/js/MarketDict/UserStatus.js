Module.define(
	"Enum",

	function () {
		NS("KrujevaDict");

		KrujevaDict.UserStatus = Static(Enum, {
			NotVerified: 1,
			Verified: 2,
			Blocked: 3,
			NotVerifyPhone: 4,

			title: function (value) {

				switch (+value) {
					case this.NotVerified:
						return "Не проверен";

					case this.Verified:
						return "Проверен";

					case this.Blocked:
						return "Заблокирован";

					case this.NotVerifyPhone:
						return "Не подтвержден телефон";
				}

				return this.Parent(value);
			}
		});
	}
);