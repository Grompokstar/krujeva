Module.define(
	"Enum",

	function () {
		NS("KrujevaDealer");

		KrujevaDealer.OrderStatus = Static(Enum, {
			NewOrder: 1,
			VerifiedOrder: 2,
			CancelledOrder: 3,

			title: function (value) {

				switch (+value) {
					case this.NewOrder:
						return 'Новый';
						break;
					case this.VerifiedOrder:
						return 'Подтвержден';
						break;
					case this.CancelledOrder:
						return 'Отклонен';
						break;
				}

			}
		});
	}
);