Module.define(
	"UI.Template",
	"Events",

	function () {
		NS("UI");

		UI.Notify = new Static(Events, {

			/*
			 arguments:
				 message @string _"New Event"

				 options @object
				 {
			 		formClass: @string, _"Police.UI.Notify.CardsArrived"
			 		formClassData: @object, _{message: "Notify"}

					template: @string,  _"Police/CardsArrived/Notify"
			 		templateData: @object, _{message: "Notify"}
					cssClass: @string
				 }
			*/
			show: function (message, options) {

				if (message != null && typeof message == "object") {
					options = message;
					message = "notify";
				}else if (!message) {
					message = "notify";
				}

				if(typeof $.notify != 'function') {
					return false;
				}

				this.formHtml(message, options, this.onHtml);

				return true;
			},

			onHtml: function(args) {
				var message = args.message;
				var html = args.html;
				var options = args.options;
				var templatename = "html";
				var formClass = args.formClass;

				options = $.extend(options, {
					clickToHide: false,
					globalPosition: "right bottom",
					autoHideDelay: 5000,
					hideDuration: 600,
					className: options.cssClass ? options.cssClass : "default"
				});

				if (formClass) {
					options.$element = formClass.$element;
					options.onDestroy = function(formClass){formClass.destroy()};
					options.onDestroyArgs = formClass;
				} else {
					$.notify.addStyle(templatename, {html: html});
					options.style = templatename;
				}

				$.notify(message, options);
			},

			formHtml: function(message, options, callbackmethod) {

				if (typeof options != "object" ) {
					options = {};
				}

				if (options.formClass) {

					( function(options, message, self, callbackmethod) {

						using(options.formClass, function () {
							var formClassData = options.formClassData ? options.formClassData : {};

							var formClass = Util.create(options.formClass, [self, {}]).init(formClassData);

							callbackmethod.call(self, {
								message: message,
								html: formClass.html(),
								options: options,
								formClass: formClass
							});
						});

					} )(options, message, this, callbackmethod);

				} else {
					var template = options.template ? options.template : "UI/Notify";
					var templateData = options.templateData ? options.templateData : {message: message};

					callbackmethod.call(this, {
						message: message,
						html: UI.Template.render(template, templateData),
						options: options
					});
				}
			}

		});
});
