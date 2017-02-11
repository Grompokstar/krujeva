Module.define(
	"UI.Widget.Forms.Control.Text",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Datepicker = Class(UI.Widget.Forms.Control.Text, {
			insertElement: function () {
				this.ParentCall();

				var changeMonth = this.$control.attr("change-month") == "yes";
				var changeYear = this.$control.attr("change-year") == "yes";
				var yearRange = this.$control.attr("year-range");

				this.$element.datepicker({
					changeMonth: changeMonth,
					changeYear: changeYear,
					yearRange: yearRange
				});
			}
		});
	}
);
