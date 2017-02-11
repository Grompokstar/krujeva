Module.define(
	function () {
		NS("GIS");

		GIS.Service = Static({
			address2Geog: function (options, callback) {
				options = Util.object(options);

				Xhr.call("GIS/Web/address2Geog", { code: options.code, house: options.house }, callback);
			},

			featureAtGeog: function (options, callback) {
				options = Util.object(options);

				Xhr.call("GIS/Web/featureAtGeog", { geog: options.geog, radius: options.radius}, callback);
			}
		});
	}
);
