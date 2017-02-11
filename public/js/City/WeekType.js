Module.define(
	"Enum",

	function () {
		NS("City");

		City.WeekType = Static(Enum, {
			Monday: 1,
			Tuesday: 2,
			Wednesday: 3,
			Thursday: 4,
			Friday: 5,
			Saturday: 6,
			Sunday: 7,

			shortTitle: function(value) {
				switch (+value) {
					case this.Monday:
						return "пн";
					case this.Tuesday:
						return "вт";
					case this.Wednesday:
						return "ср";
					case this.Thursday:
						return "чт";
					case this.Friday:
						return "пт";
					case this.Saturday:
						return "сб";
					case this.Sunday:
						return "вс";
				}

				return value;
			},

			title: function (value) {
				switch (+value) {
					case this.Monday:
						return "Понедельник";
					case this.Tuesday:
						return "Вторник";
					case this.Wednesday:
						return "Среда";
					case this.Thursday:
						return "Четверг";
					case this.Friday:
						return "Пятница";
					case this.Saturday:
						return "Суббота";
					case this.Sunday:
						return "Воскресенье";
				}

				return this.Parent(value);
			}
		});
	}
);