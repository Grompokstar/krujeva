Module.define(
	"Data.ServerSource",
	"Security.AccessMode",

	function () {
		NS("Security.Data");

		Security.Data.Urls = Class(Data.ServerSource, {
			url: "Security/Web/Urls/",

			create: function () {
				return {
					access: Security.AccessMode.Execute
				}
			}
		});
	}
);
