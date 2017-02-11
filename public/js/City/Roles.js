Module.define(
	"Enum",

	function () {
		NS("City");

		City.Roles = Static(Enum, {
			Nanny: 1,
			Control: 2,
			Owner: 3,
			ManagementCompany: 4
		});
	}
);