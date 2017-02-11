Module.define(
	"Enum",

	function () {
		NS("City");

		City.Periods = Static(Enum, {
			FullTime: 1,
			HalfTime: 2
		});
	}
);