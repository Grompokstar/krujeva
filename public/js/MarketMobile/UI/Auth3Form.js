Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.FormValidate",

	function () {
		NS("KrujevaMobile.UI");


		KrujevaMobile.UI.Auth3Form = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/Auth3Form",

			fields: {
				'organizationname': ['require'],
				'name': ['require'],
				'inn': ['require']
			},

			afterRender: function () {
				this.listenScroll();

				if (application.registrationData) {
					this.setRecord(application.registrationData);
				}

				this.getLocation();
			},

			backClick: function () {
				application.backOpenPage();
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {

					return;
				}

				application.registrationData = Util.merge(application.registrationData, record, true);

				application.open('auth4', {animate: true});
			},

			validateField: function (name, val) {
				var result = this.ParentCall();

				switch (name) {

					case 'inn':
						result = Util.isNumber(val);
						break;
				}

				return result;
			},

			getLocation: function () {

				if (navigator.geolocation) {

					navigator.geolocation.getCurrentPosition(this.savePosition);

					return true;

				} else {

					return false;
				}
			},

			savePosition: function (position) {

				var lat = position.coords.latitude;

				var lng = position.coords.longitude;

				Xhr.call("/Cities/nearest", {lat: lat, lng: lng}, function (res) {

					if (res && res.data) {

						var data = res.data;

						var reqData = {
							lat: lat,
							lng: lng
						};

						if (data.street) {
							reqData['address'] = data.street;
						}

						if (data.city) {
							reqData['city'] = data.city['name'];

							reqData['cityid'] = data.city['id'];
						}

						application.registrationData = Util.merge(application.registrationData, reqData, true);
					}

				});

			},

		});
	}
);