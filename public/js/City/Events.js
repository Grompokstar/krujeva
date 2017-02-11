Module.define(
	"Message",

	function () {
		NS("City");

		City.Events = Static(Events, {

			init: function () {

				var data = City.Data;

				for (var record in data) if (data.hasOwnProperty(record)) {

					this.listenDataEvent("City."+ record +".Insert", 'City.Data.'+ record);
					this.listenDataEvent("City."+ record +".Update", 'City.Data.'+ record);
					this.listenDataEvent("City."+ record +".Remove", 'City.Data.'+ record);
				}
			},

			listenDataEvent: function (messageEvent, sourceClass, options) {
				options = options || {};

				Message.on(messageEvent, function (data) {
					var id = data.id;

					//console.log("Message", messageEvent, this.isSubscriptions(messageEvent));

					if (!this.isSubscriptions(messageEvent) && !options.listen) {
						return;
					}

					var removeEvent = false;

					if (messageEvent.indexOf(".Remove") !== -1) {
						removeEvent = true;
					}

					var source = Util.create(sourceClass);

					source.get({id: id, events: true, removeEvent: removeEvent}, function (data) {
						data = data || {};

						if (data.stats) {
							this.emit("Stats.Update", {stats: data.stats});
						}

						if (data.item) {

							this.emit(messageEvent, {
								item: data.item
							});

						} else if (removeEvent) {
							this.emit(messageEvent, {id: id});
						}

					}.bind(this));

				}.bind(this));
			},

			statsCount: function (name, object) {
				if (!Util.isObject(object)) {
					return 0;
				}

				var value = Util.Object.value(object, name);

				var calcCount = function (obj) {
					var count = 0;

					if (Util.isObject(obj)) {

						Util.each(obj, function (statC) {

							if (Util.isObject(statC)) {
								count += calcCount(statC);
							} else {
								count += statC;
							}
						});

					} else {
						count = obj;
					}

					return count;
				};

				return calcCount(value) || 0;
			}
		});
	}
);
