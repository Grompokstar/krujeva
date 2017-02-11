Module.define(
	"KrujevaMobile.Widget.UpdateVersionForm",

	function () {
		NS("KrujevaMobile");

		KrujevaMobile.MobileUpdate = Static({

			init: function () {

				if (!this.showUpdate()) {
					return;
				}

				var data = context('mobile');

				var mode = data.mode ? data.mode : 'light';

				if (mode == 'light') {

					return;
				}

				setTimeout(function () {

					application.showWidget("KrujevaMobile.Widget.UpdateVersionForm", {animate: true});

				}, 4000);
			},

			showUpdate: function () {
				var data = context('mobile');

				if (!data) {
					return false;
				}

				if (!data.version) {
					return false;
				}

				if (data.version <= application.MOBILE_VERSION) {
					return false;
				}

				return true;
			},

			updateApp: function () {

				if (Util.isIOS()) {

					window.open("itms-apps://itunes.apple.com/us/app/kvik-dla-salonov-krasoty/id1109572517", '_system');

				} else {

					navigator.app.loadUrl("market://details?id=ru.kvik.hair", {openExternal: true});
				}
			}

		})
	}
);