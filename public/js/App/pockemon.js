this.findNearbyPokemon = function (t, n, s, r) {
	var o = $('.home-map-loading'), i = o.find('.loading-message'), a = $('.home-map-scan');
	if (e.loading) {
		return
	}
	;
	if (e.scanning && !r) {
		return
	}
	;
	if (!e.scanning && e.loadingTimer) {
		clearInterval(e.loadingTimer);
		e.loadingTimer = null
	}
	;
	e.loading = !0;
	if (o.is(':hidden')) {
		o.addClass('home-map-loading-mini');
		o.fadeIn(200)
	}
	;
	if (s) {
		e.scanning = !0;
		a.attr('disabled', !0);
		a.find('strong').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
		return App.request('/map/scan/' + t + '/' + n, function (i) {
			e.loading = !1;
			e.loadingTimer = setTimeout(function () {
				e.findNearbyPokemon(t, n, !1, i.jobId)
			}, e.TIMER_JOB)
		}, function (o) {
			if (o.indexOf('{disabled}') > -1) {
				i.text('Scanning is currently disabled temporarily, retrying in ' + (e.TIMER_ERROR / 1000) + ' seconds.');
				i.css('display', 'inline-block')
			} else if (o.indexOf('{scan-throttle}') > -1) {
				i.text('You already scanned recently, retrying in ' + (e.TIMER_ERROR / 1000) + ' seconds.');
				i.css('display', 'inline-block')
			} else {
				i.text('Unable to send scan request due to an internal error, retrying in ' + (e.TIMER_ERROR / 1000) + ' seconds.');
				i.css('display', 'inline-block')
			}
			;
			e.loading = !1;
			e.loadingTimer = setTimeout(function () {
				e.scanning = !1;
				e.findNearbyPokemon(t, n, !0)
			}, e.TIMER_SCAN_ERROR)
		})
	}
	;
	return App.request('/map/data/' + t + '/' + n + (r ? '/' + r : ''), function (s) {
		if (s.jobStatus) {
			if (s.jobStatus == 'failure' || s.jobStatus == 'unknown') {
				i.text('Unable to scan for pokemon, retrying in ' + (e.TIMER_SCAN_ERROR / 1000) + ' seconds. If this continues to fail then the Pokemon servers are currently unstable or offline.');
				i.css('display', 'inline-block');
				e.loading = !1;
				e.loadingTimer = setTimeout(function () {
					e.scanning = !1;
					e.findNearbyPokemon(t, n, !0)
				}, e.TIMER_SCAN_ERROR)
			} else if (s.jobStatus == 'in_progress') {
				i.text('');
				i.hide();
				e.loading = !1;
				e.loadingTimer = setTimeout(function () {
					e.findNearbyPokemon(t, n, !1, r)
				}, e.TIMER_JOB)
			}
			;
			return
		}
		;
		for (var d in s.pokemon) {
			var l = !1;
			for (var m in e.pokemon) {
				if (e.pokemon[m].id == s.pokemon[d].id) {
					l = !0
				}
			}
			;
			if (!l) {
				e.pokemon.push(s.pokemon[d])
			}
		}
		;
		e.updateMarkers();
		if (e.scanning) {
			i.text('');
			i.hide();
			App.success('Scan complete! You can re-scan the area for new pokemon that spawn soon.');
			a.addClass('is-on-cooldown');
			a.find('strong').text('Click To Find PokГ©mon Near Marker');
			setTimeout(function () {
				a.removeClass('is-on-cooldown');
				a.removeAttr('disabled')
			}, e.TIMER_SCAN_DELAY)
		}
		;
		e.scanning = !1;
		e.loading = !1;
		e.loadingTimer = null;
		if (o.is(':visible')) {
			o.fadeOut(200)
		}
	}, function (o) {
		i.text('Unable to process response. We are aware of this issue and trying to fix. Please try to refresh the page to potentially solve issue!');
		i.css('display', 'inline-block');
		e.loading = !1;
		e.loadingTimer = setTimeout(function () {
			e.findNearbyPokemon(t, n, !1, r)
		}, e.TIMER_ERROR)
	})
};


this.updateMarkers = function () {
	if (!e.map) {
		return
	}
	;
	for (var i in e.pokemon) {
		var n = e.pokemon[i], o = n.expiration_time - Math.floor(+new Date() / 1000), t = e.markers['pokemon-' + i];
		if (o <= 0) {
			if (t) {
				e.map.removeLayer(t);
				delete e.markers['pokemon-' + i]
			}
			;
			delete e.pokemon[i];
			continue
		}
		;
		if (!t) {
			t = e.createMarker(i, n)
		}
		;
		t.updateLabel(e.secondsToString(o))
	}
}