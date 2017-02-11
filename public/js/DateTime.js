Module.define(

	function () {

		DateTime = Static({
			config: {
				months: "января_февраля_марта_апреля_мая_июня_июля_августа_сентября_октября_ноября_декабря".split("_"),
				monthsShort: "янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек".split("_"),
				weekdays: "воскресенье_понедельник_вторник_среда_четверг_пятница_суббота".split("_"),
				weekdaysShort: "пон._вто._сре._чет._пят._суб._вос.".split("_"),
				weekdaysMin: "Пн_Вт_Ср_Чт_Пт_Сб_Вс".split("_"),
				longDateFormat: {
					LT: "HH:mm",
					L: "DD/MM/YYYY",
					LL: "D MMMM YYYY",
					LLL: "D MMMM YYYY LT",
					LLLL: "dddd D MMMM YYYY LT"
				},
				calendar: {
					sameDay: "[Aujourd'hui à] LT",
					nextDay: '[Demain à] LT',
					nextWeek: 'dddd [à] LT',
					lastDay: '[Hier à] LT',
					lastWeek: 'dddd [dernier à] LT',
					sameElse: 'L'
				},
				relativeTime: {
					future: "в %s",
					past: "%s назад",
					s: "секунда",
					m: "минута",
					mm: "%d минут",
					h: "час",
					hh: "%d часов",
					d: "день",
					dd: "%d дней",
					M: "месяц",
					MM: "%d месяцев",
					y: "год",
					yy: "%d лет"
				},
				ordinal: function (number) {
					return number + (number === 1 ? 'er' : 'ème');
				},
				week: {
					dow: 1 // Monday is the first day of the week.
				}
			},

			utcTime: true,

			getTime: function (time) {
				var time = time || null;

				if (!time) {
					return null
				}

				var arr = time.split(':');
				if (!arr[1]) {
					return null
				}

				return arr[0] + ':' + arr[1];
			},

			parse: function (value) {
				if (value == null || value == 0 || value == undefined) {
					value = '00:00';
				}

				var time = value.split(":");

				if (!time[1]) {
					return null;
				}

				return { hh: time[0], mm: time[1] };
			},

			time2minutes: function (time) {
				var time = this.parse(time);

				if (!time.hh) {
					return 0;
				}

				var minutes = Math.floor(time.mm.valueOf());
				minutes += (Math.floor(time.hh.valueOf()) * 60);
				return minutes;
			},

			takeMinutesToTime: function (time, minutes) {
				time = this.getTime(time);
				var minutesTime = this.time2minutes(time);
				return this.minutes2time((minutesTime - parseInt(minutes)));
			},

			//минуты превращаются во время (135мин = 02:15)
			minutes2time: function (minutes) {
				if (minutes == null || minutes == 0 || minutes == undefined || isNaN(parseInt(minutes))) {
					return '00:00';
				}

				var hours = Math.floor(minutes / 60);
				minutes = minutes - (60 * hours);

				var h = ('' + hours).split('');
				var m = ('' + minutes).split('');

				if (!h[1]) {
					hours = '0' + ('' + hours);
				}

				if (!m[1]) {
					minutes = '0' + ('' + minutes);
				}

				return hours + ':' + minutes;
			},

			addMinutesToTime: function (time, minutes) {
				time = this.getTime(time);
				return this.sumTimesNormal(time, this.minutes2time(minutes));
			},

			//сложение времени
			/* примеры
			 *  10:00 + 1:00 = 11:00
			 *  10:00 + 20:00 = 06:00
			 * */
			sumTimes: function (t1, t2) {

				var d1 = new Date(0);
				var d12 = new Date(0);
				var d2 = new Date(0);

				d1.setHours(this.parse(t1).hh);
				d1.setMinutes(this.parse(t1).mm);

				d12.setHours(0);

				d2.setHours(this.parse(t2).hh);
				d2.setMinutes(this.parse(t2).mm);

				// .getTime - number of milliseconds since 1970
				var result = d1.getTime() - d12.getTime() + d2.getTime();

				// make date object from milliseconds
				var newDate = new Date(result);

				var hh = "" + newDate.getHours();
				var mm = "" + newDate.getMinutes();

				if (hh.length < 2) {
					hh = "0" + hh;
				}
				if (mm.length < 2) {
					mm = "0" + mm;
				}

				return {hh: hh, mm: mm}
			},

			sumTimesNormal: function (t1, t2) {
				var time = this.sumTimes(t1, t2);
				return time.hh + ':' + time.mm;
			},

			getCurrentDate: function (outFormat) {
				var outFormat = outFormat || 'DD.MM.YYYY';

				if (typeof ServerTime !== 'undefined') {

					var date = this.momentObject(ServerTime.now(), 'x');

				} else {

					var date = this.momentObject();

				}

				return date.format(outFormat)
			},

			getDateFromTimestamp: function (timestamp, outformat) {
				var outFormat = outformat || 'DD.MM.YYYY';
				var date = this.momentObject(timestamp, 'x');
				return date.format(outFormat)
			},

			getDate: function (date, outFormat, inFormat) {
				var self = this;
				var date = date || null;
				var outFormat = outFormat || 'DD.MM.YYYY';
				var inFormat = inFormat || 'YYYY-MM-DD';

				if (!date) {
					return null
				}

				var date = this.momentObject(date, inFormat);

				return date.format(outFormat);
			},

			momentDate: function (date, inFormat) {
				inFormat = inFormat || 'YYYY-MM-DD';

				return this.momentObject(date, inFormat);
			},

			momentObject: function (date, inFormat, useUtc) {
				moment.locale('ru', this.config);

				useUtc = typeof useUtc == 'undefined' ? this.utcTime : useUtc;

				if (date) {
					var m = new moment(date, inFormat);
				} else {
					var m = new moment();
				}

				if (useUtc) {
					m.utcOffset(0);
				}

				return m;
			}
		});
	});
