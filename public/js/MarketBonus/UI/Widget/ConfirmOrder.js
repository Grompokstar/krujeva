Module.define("Base.Form",

	function () {

		NS("KrujevaBonus.UI.Widget");

		KrujevaBonus.UI.Widget.ConfirmOrder = Class(Base.Form, {
			template: "KrujevaBonus/Widget/ConfirmOrder",

			fields: {
				localdeliverydate: "Дата доставки заказа",
				orderid: "Заявка"
			},

			rules: {
				edit: {
					localdeliverydate: ['require'],
					orderid: ['require']
				}
			},

			order: null,

			initialize: function (parent, options) {
				this.ParentCall();

				var dateObj = DateTime.momentDate(DateTime.getCurrentDate('DD.MM.YYYY'), 'DD.MM.YYYY');

				var date  = dateObj.add(1, 'days').format('DD.MM.YYYY');

				var record = {localdeliverydate: date};

				if (options && options.orderid) {

					record['orderid'] = options.orderid;
				}

				if (options && options.order) {

					this.order = options.order;
				}

				this.setRecord(record);
			},

			afterRender: function () {
				this.ParentCall();

				var dateToday = new Date();

				var dates = this.fieldElement('localdeliverydate').datepicker({
					minDate: dateToday
				});
			},

			initCSS: function (options) {

				if (options && options.animate) {

					this.$element.css({opacity: 0});

					this.$name('container').css({
						x: 450
					});
				}
			},

			show: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.initCSS(options);

					this.$element.transition({opacity: 1}, 250);

					this.$name('container').transition({
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

					this.$element.transition({opacity: 0}, 250);

					this.$name('container').transition({
						x: 450
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

			cntClick: function (caller, ev) {
				ev.preventDefault();
				ev.stopPropagation();
			},

			bgClick: function () {
				this.destroy({animate: true});
			},

			cancelClick: function () {
				this.destroy({animate: true});
			},

			verifyClick: function () {
				this.setFieldsValue();

				var record = this.getRecord();

				if (!this.validate('edit')) {
					this.render();
					return;
				} else {
					this.hideErrors();
				}

				var loader = Loader.start(this.$name('form-loader'), {showTimeout: 0});

				var source = new KrujevaBonus.Data.BonusOrders();

				source.bonusconfirm({item: record, order: this.order}, function (data, result) {

					Loader.end(loader, function () {

						if (data) {

							Alert.success('Заказ подтвержден');

							this.emit('confirmed');

							this.destroy();
						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

							}

						}

					}.bind(this));

				}.bind(this));
			}

		});
	});