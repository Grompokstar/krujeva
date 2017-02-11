Module.define(
	"Base.Form",

	function () {

		NS("KrujevaDict.UI.Widget");

		KrujevaDict.UI.Widget.EditUsersForm = Class(Base.Form, {
			template: "KrujevaDict/Widget/EditUsersForm",

			fields: {
				id: "id",
				areaname: "areaname",
				barbershop: "barbershop",
				cityname: "cityname",
				createddatetime: "createddatetime",
				phone: "phone",
				salonname: "salonname",
				inn: "inn",
				status: "status",
				organizationname: "organizationname",
			},

			rules: {
				edit: {
					name: ['require'],
					parentid: ['integerOnly']
				}
			},

			initialize: function (parent, options) {
				this.ParentCall();

				if (options && options.item) {

					var item = Util.clone(options.item);

					//@barbershop
					if (item.barbershop) {
						item.barbershop = JSON.parse(item.barbershop);

						item.cityname = item.barbershop.cityname;

						item.barbershop = item.barbershop.address;
					}

					this.setRecord(item);
				}
			},

			initCSS: function (options) {

				if (options && options.animate) {

					this.$element.css({
						x: 400,
						zIndex: options.zIndex
					});
				}
			},

			show: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.initCSS(options);

					this.$element.transition({
						x: 0
					}, 250, function () {

						this.CallMethod(parentMethod, options);

						if (Util.isFunction(callback)) {
							callback();
						}

					}.bind(this));

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}

				}
			},

			destroy: function (options, callback) {
				var parentMethod = this.ParentMethod();

				this.hide(options, function () {

					this.CallMethod(parentMethod);

					if (Util.isFunction(callback)) {
						callback();
					}

				}.bind(this));
			},

			hide: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.$element.transition({
						x: 400,
						opacity: 0
					}, 300, function () {

						this.CallMethod(parentMethod, options);

						if (Util.isFunction(callback)) {
							callback();
						}

					}.bind(this));

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}
				}
			},

			closeClick: function () {
				this.destroy({animate: true});
			},


			changeStatusClick: function (caller) {

				var id = $(caller).data('id');

				var status = $(caller).data('status');

				var source = new KrujevaDict.Data.Users();

				var loader = Loader.start(this.$name('form-loader'));

				source.changestatus({id: id, status: status}, function (res) {

					Loader.end(loader, function () {

						if (res) {

							Alert.success('Сохранено');

							Message.emit("KrujevaDict.Users.Update");

							this.emit('item:change');

							this.destroy({animate: true});

						} else {
							Alert.error('Не удалось сохранить');
						}

					}.bind(this));

				}.bind(this));

			}

		});
});