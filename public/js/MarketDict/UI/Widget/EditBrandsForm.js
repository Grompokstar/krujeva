Module.define(
	"Base.Form",

	function () {

		NS("KrujevaDict.UI.Widget");

		KrujevaDict.UI.Widget.EditBrandsForm = Class(Base.Form, {
			template: "KrujevaDict/Widget/EditBrandsForm",

			fields: {
				id: "id",
				name: "Название",
				parentid: "Родительский бренд",
				code: "Системный код"
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
					this.setRecord(options.item);
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

			removeClick: function () {

				if (!confirm('Удалить?')) {
					return;
				}

				var source = new KrujevaDict.Data.Brands();

				var record = this.getRecord();

				var loader = Loader.start(this.$name('form-loader'));


				source.remove({id: record.id}, function (res) {

					Loader.end(loader, function () {

						if (res && res.item) {

							Alert.success('Удалено');

							Message.emit("KrujevaDict.Brands.Insert", {item: res.item});

							this.emit('item:change');

							this.destroy({animate: true});

						} else {
							Alert.error('Не удалось удалить');
						}

					}.bind(this));

				}.bind(this));

			},


			saveClick: function () {
				this.setFieldsValue();

				if (!this.validate('edit')) {
					this.render();
					return false;
				} else {
					this.hideErrors();
				}

				var source = new KrujevaDict.Data.Brands();

				var record = this.getRecord();

				var loader = Loader.start(this.$name('form-loader'));

				var method = record.id ? 'update' : 'insert';

				source[method](record, function (res) {

					Loader.end(loader, function () {

						if (res && res.item) {

							Alert.success('Сохранено');

							Message.emit("KrujevaDict.Brands.Insert", {item: res.item});

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