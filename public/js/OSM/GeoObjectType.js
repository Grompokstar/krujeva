Module.define(
	"Enum",

	function () {
		NS("OSM");

		OSM.GeoObjectType = Static(Enum, {
			Country: 1,
			Region: 2,
			District: 3,
			Village: 4,
			Hamlet: 5,
			City: 6,
			Street: 7,
			Building: 8
		});
	}
);