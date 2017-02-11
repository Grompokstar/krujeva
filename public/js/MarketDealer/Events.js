Module.define(
	"Message",

	function () {
		NS("KrujevaDealer");

		KrujevaDealer.Events = Static(Events, {

			init: function () {

				var data = KrujevaDealer.Data;

				for (var record in data) if (data.hasOwnProperty(record)) {

					this.listenDataEvent("Krujeva."+ record +".Insert", 'KrujevaDealer.Data.'+ record, {listen: true});
					this.listenDataEvent("Krujeva."+ record +".Update", 'KrujevaDealer.Data.'+ record, {listen: true});
					this.listenDataEvent("Krujeva."+ record +".Remove", 'KrujevaDealer.Data.'+ record, {listen: true});
				}

                this.listenOrderEvent();

				this.listenDataEvent("Krujeva.Orders.Cancelled", 'KrujevaDealer.Data.Orders', {listen: true});
				this.listenDataEvent("Krujeva.Orders.Confirmed", 'KrujevaDealer.Data.Orders', {listen: true});
			},

            listenOrderEvent: function () {

                var messageEvent = 'Krujeva.Orders.NotNew';

                Message.on(messageEvent, function (data) {

                    this.emit("Stats.Update", {stats: {neworders: data.neworders}});

                    this.emit(messageEvent, data);

                }.bind(this));
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
