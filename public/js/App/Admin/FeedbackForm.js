Module.define(

	function () {
		NS("App.Admin");

		App.Admin.FeedbackForm = Class(App.BaseForm, {

			initialize: function () {
				this.$container = $('body');

				this.initInteractionEvents();
			},

			downloadClick: function (caller) {

				var form = $("<form>", {
					target: "_blank",
					method: "post",
					action: "/Feedback/listItems"
				});

				//form.append('<input name="dealerbrandid" value="' + dealerbrandid + '"/>');

				form.submit();
			},

		});
	});
