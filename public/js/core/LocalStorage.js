Module.define(
	"Events",

	function () {

		GLOBAL.LocalStorage = Static(Events,{

			get: function (key) {

				if (!this.verify()) {
					return null;
				}

				var value = localStorage.getItem(key);

				try {

					value = JSON.parse(value);

				}catch(e) {

					value = null;
				}

				return value;
			},

			set: function (key, value) {
				if (!this.verify()) {
					return null;
				}

				localStorage.setItem(key, JSON.stringify(value));
			},

			remove: function (key) {
				if (!this.verify()) {
					return null;
				}

				localStorage.removeItem(key);
			},

			verify: function () {
				try {
					return 'localStorage' in window && window['localStorage'] !== null;
				} catch (e) {
					return false;
				}
			}
		});
	}
);
