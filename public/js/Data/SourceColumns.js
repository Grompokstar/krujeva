Module.define(function () {
	NS("Data");

	Data.SourceColumns = Class({
		source: null,
		columns: [],
		columnNames: {},

		importColumns: function (columns) {
			var column;
			var field;
			var i;

			if (columns) {
				this.columns = columns;
			}

			if (this.columns && this.columns.length) {
				for (i = 0; i < this.columns.length; i++) {
					column = this.columns[i];
					field = this.source.fieldNames[column.name];

					if (field) {
						Util.each(["title", "type", "readonly", "text"], function (name) {
							column[name] = Util.coalesce(column[name], field[name]);
						});
					}
				}
			} else {
				this.columns = [];

				for (i = 0; i < this.source.fields.length; i++) {
					this.columns.push(Util.clone(this.source.fields[i]));
				}
			}

			for (i = 0; i < this.columns.length; i++) {
				column = this.columns[i];

				this.columnNames[column.name] = column;
			}
		},

		columnTitle: function (name) {
			var column = this.columnNames[name];

			return column ? column.title : "";
		},

		columnType: function (name) {
			var column = this.columnNames[name];

			return column ? column.type : "";
		},

		columnValue: function (name, item) {
			var column = this.columnNames[name];

			if (column) {
				if (typeof(column.text) == "function") {
					return column.text(item);
				}

				return item[name];
			}

			return null;
		},

		columnText: function (name, item) {
			return Util.coalesce(this.columnValue(name, item), "");
		},

		columnHidden: function (name) {
			var column = this.columnNames[name];

			return column ? column.hidden : false;
		},

		columnReadonly: function (name) {
			var column = this.columnNames[name];

			return column ? column.readonly : false;
		}
	});
});
