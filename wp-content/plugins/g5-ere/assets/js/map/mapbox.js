var G5ERE_MAP = G5ERE_MAP || {};
(function ($) {
	'use strict';

	G5ERE_MAP = {
		options: {
			locations: [],
			zoom: !isNaN(parseInt(g5ere_map_config.zoom, 10)) ? parseInt(g5ere_map_config.zoom, 10) : 12,
			minZoom: 0,
			skin: g5ere_map_config.skin,
			gestureHandling: 'cooperative',// "greedy",
			cluster_markers: g5ere_map_config.cluster_markers,
			draggable: true,
			scrollwheel: true,
			navigationControl: true,
			mapTypeControl: false,
			streetViewControl: false,
		},
		instances: [],
		skins: [],
		getInstance: function (id) {
			for (var i = 0; i < this.instances.length; i++) {
				if (this.instances[i].id === id) {
					return this.instances[i];
				}
			}
			return false;
		},
		getSkin: function (skin) {
			return G5ERE_MAP.skins[skin] ? G5ERE_MAP.skins[skin] : G5ERE_MAP.skins.skin1
		},
		addListener: function (el, e, t) {
			el.on(e, function (e) {
				t(e);
			});
		}
	};

	G5ERE_MAP.skins = {
		skin1: "mapbox://styles/mapbox/streets-v10",
		skin2: "mapbox://styles/mapbox/outdoors-v10",
		skin3: "mapbox://styles/mapbox/light-v9",
		skin4: "mapbox://styles/mapbox/dark-v9",
		skin6: "mapbox://styles/mapbox/satellite-streets-v10",
		skin7: "mapbox://styles/mapbox/navigation-preview-day-v4",
		skin8: "mapbox://styles/mapbox/navigation-preview-night-v4",
		skin9: "mapbox://styles/mapbox/navigation-guidance-day-v4",
		skin10: "mapbox://styles/mapbox/navigation-guidance-night-v4",
		skin12: ""
	};


	/*
	* G5ERE_MAP.LatLng
	*/
	G5ERE_MAP.LatLng = function (latitude, longitude) {
		this.init(latitude, longitude);
	};

	G5ERE_MAP.LatLng.prototype.init = function (latitude, longitude) {
		this.latitude = latitude;
		this.longitude = longitude;
		this.latlng = new mapboxgl.LngLat(longitude, latitude);
	};

	G5ERE_MAP.LatLng.prototype.getLatitude = function () {
		return this.latlng.lat;
	};


	G5ERE_MAP.LatLng.prototype.getLongitude = function () {
		return this.latlng.lng;
	};

	G5ERE_MAP.LatLng.prototype.toGeocoderFormat = function () {
		return [this.getLongitude(), this.getLatitude()].join(",");
	};

	G5ERE_MAP.LatLng.prototype.getSourceObject = function () {
		return this.latlng;
	};

	/*
	* G5ERE_MAP.LatLngBounds
	*/
	G5ERE_MAP.LatLngBounds = function (southwest, northeast) {
		this.init(southwest, northeast)
	};

	G5ERE_MAP.LatLngBounds.prototype.init = function (southwest, northeast) {
		this.southwest = southwest;
		this.northeast = northeast;
		this.bounds = new mapboxgl.LngLatBounds(southwest, northeast);
	};

	G5ERE_MAP.LatLngBounds.prototype.extend = function (e) {
		this.bounds.extend(e.getSourceObject());
	};

	G5ERE_MAP.LatLngBounds.prototype.getSourceObject = function () {
		return this.bounds;
	};

	/*
	* G5ERE_MAP.Popup
	*/
	G5ERE_MAP.Popup = function (e) {
		this.options = $.extend(true, {
			content: "",
			classes: "g5ere__map-popup-wrap",
			position: false,
			map: false,
			anchor: 'bottom',
			offset: [0, -60]
		}, e);
		this.init(e);
	};

	G5ERE_MAP.Popup.prototype.init = function (e) {
		this.popup = new mapboxgl.Popup({
			className: this.options.classes,
			closeButton: !1,
			closeOnClick: !1,
			anchor: this.options.anchor,
			offset: this.options.offset
		});

		this.timeout = null;
		if (this.options.position) {
			this.setPosition(this.options.position);
		}

		if (this.options.content) {
			this.setContent(this.options.content);
		}

		if (this.options.map) {
			this.setMap(this.options.map);
		}
	};

	G5ERE_MAP.Popup.prototype.setContent = function (e) {
		this.popup.setHTML(e);
		return this;
	};

	G5ERE_MAP.Popup.prototype.setPosition = function (e) {
		this.popup.setLngLat(e.getSourceObject());
		return this;
	};

	G5ERE_MAP.Popup.prototype.setMap = function (e) {
		this.options.map = e;
		return this;
	};


	G5ERE_MAP.Popup.prototype.remove = function () {
		this.popup.remove();
		return this;
	};

	G5ERE_MAP.Popup.prototype.show = function () {
		var t = this;
		return clearTimeout(t.timeout), t.popup.addTo(t.options.map.getSourceObject()), setTimeout(function () {
			t.popup._container.className += ' show'
		}, 10), t

	};

	G5ERE_MAP.Popup.prototype.hide = function () {
		var t = this;
		return clearTimeout(t.timeout), void 0 !== t.popup._container && (t.popup._container.className = t.popup._container.className.replace("show", "")), t.timeout = setTimeout(function () {
			t.remove()
		}, 250), t

	};

	/*
	* G5ERE_MAP.Clusterer
	*/
	G5ERE_MAP.Clusterer = function (e) {
		this.init(e);
	};


	G5ERE_MAP.Clusterer.prototype.init = function (e) {
		this.map = e;
		this.clusters = {};
		this.clusterer = !1;
	};

	G5ERE_MAP.Clusterer.prototype.load = function () {
		this.clusterer = new Supercluster({
			radius: 80,
			maxZoom: 20
		});
		this.clusterer.load(this.getMarkers());
		this.update();
	};

	G5ERE_MAP.Clusterer.prototype.destroy = function () {
		this.clusterer = !1;
	};

	G5ERE_MAP.Clusterer.prototype.update = function (_zoom) {
		if (!this.clusterer) return !1;
		if (typeof _zoom === 'undefined') {
			_zoom = this.map.getZoom();
		}
		var _bounds = this.map.getBounds(),
			_bbox = [_bounds.getWest(), _bounds.getSouth(), _bounds.getEast(), _bounds.getNorth()],
			features = this.clusterer.getClusters(_bbox, _zoom);
		this.removeFeatures();
		this.displayFeatures(features);
	};

	G5ERE_MAP.Clusterer.prototype.removeFeatures = function () {
		this.map.removeMarkers();
		Object.keys(this.clusters).forEach(function (t) {
			this.clusters[t].remove();
		}.bind(this));
	};

	G5ERE_MAP.Clusterer.prototype.displayFeatures = function (t) {
		var e = this;
		t.forEach(function (t) {
			if (t.properties.cluster) {
				var i = document.createElement('div');
				i.className = 'g5ere__cluster';
				i.innerHTML = t.properties.point_count_abbreviated;
				var n = new mapboxgl.Marker(i);
				n.setLngLat(t.geometry.coordinates);
				n.addTo(e.map.getSourceObject());
				e.clusters[t.properties.cluster_id] = n;
				i.addEventListener('click', function (i) {
					i.preventDefault();
					e.map.getSourceObject().easeTo({
						center: t.geometry.coordinates,
						zoom: e.clusterer.getClusterExpansionZoom(t.properties.cluster_id)
					});
				});
			} else {
				var marker = new G5ERE_MAP.Marker({
					map: e.map,
					popup: t.properties.marker.popup,
					position: new G5ERE_MAP.LatLng(t.geometry.coordinates[1], t.geometry.coordinates[0]),
					template: t.properties.marker.template,
					draggable: t.properties.marker.draggable
				});
				e.map.markers.push(marker);
			}
		});
	};

	G5ERE_MAP.Clusterer.prototype.getMarkers = function () {
		return this.map.markers.map(function (t, e) {
			return {
				type: "Feature",
				geometry: {
					type: "Point",
					coordinates: [t.getPosition().longitude, t.getPosition().latitude]
				},
				properties: {
					sID: e + 1,
					scID: 0,
					marker: {
						template: t.options.template,
						popup: t.options.popup,
						draggable: t.options.draggable
					}
				}
			}
		})
	};

	/*
	* G5ERE_MAP.Marker
	*/
	G5ERE_MAP.Marker = function (e) {
		var newConfigMarker = $.parseJSON(JSON.stringify(g5ere_map_config.marker));
		this.options = $.extend(true, {
				position: false,
				map: false,
				popup: false,
				animation: false, // 'Drop' | 'Bounce' | 'Wobble'
				draggable: false,
				template: {
					type: 'basic', // 'simple'| 'basic'
					marker: newConfigMarker,
					id: ''
				}
			}
			, e);
		this.init(e);
	};

	G5ERE_MAP.Marker.prototype.init = function (e) {
		if (this.options.template.type === 'basic') {
			this.marker = new mapboxgl.Marker({
				element: this.template(),
				draggable: this.options.draggable
			});
		} else {
			this.marker = new mapboxgl.Marker({
				draggable: this.options.draggable
			});
		}

		if (this.options.position) {
			this.setPosition(this.options.position);
		}

		if (this.options.map) {
			this.setMap(this.options.map);
		}
	};

	G5ERE_MAP.Marker.prototype.getPosition = function () {
		return this.options.position;
	};

	G5ERE_MAP.Marker.prototype.setPosition = function (e) {
		this.marker.setLngLat(e.getSourceObject());
		return  this;
	};

	G5ERE_MAP.Marker.prototype.setMap = function (e) {
		this.marker.addTo(e.getSourceObject());
		return this;
	};

	G5ERE_MAP.Marker.prototype.remove = function () {
		if (this.options.popup) {
			this.options.popup.remove();
		}
		this.marker.remove();
		return this;
	};

	G5ERE_MAP.Marker.prototype.getSourceObject = function () {
		return this.marker;
	};

	G5ERE_MAP.Marker.prototype.getTemplate = function () {
		var e = document.createElement("div");
		e.className = "g5ere__marker-container";
		e.style.position = "absolute";
		e.style.cursor = "pointer";
		e.style.zIndex = 10;
		e.id = this.options.template.id;
		var t = $("#g5ere__marker_template").html().replace("{{icon}}", this.options.template.marker.html).replace("{{type}}", this.options.template.marker.type);

		$(e).append(t);
		return e;
	};

	G5ERE_MAP.Marker.prototype.template = function () {
		var t = this.getTemplate();
		var self = this;
		t.addEventListener("click", function (e) {
			e.preventDefault();
			self.showPopup();
		});
		return t;

	};

	G5ERE_MAP.Marker.prototype.fitPopupToScreen = function () {
		/*var t = 130,
			e = 360,
			i = 130,
			n = this.options.map.getSourceObject(),
			o = n.getCanvas().getBoundingClientRect(),
			s = n.project(n.getCenter()),
			a = s.x,
			r = s.y,
			p = n.project(this.marker.getLngLat());
		o.width - p.x < e - 1 && (s.x += e - (o.width - p.x)), p.y < t - 1 && (s.y -= t - p.y), o.height - p.y < i - 1 && (s.y += i - (o.height - p.y)), s.x === a && s.y === r || n.panTo(n.unproject(s), {
			duration: 200
		})*/
	};

	G5ERE_MAP.Marker.prototype.showPopup = function() {
		var self = this;
		if (this.options.popup && this.options.map && this.options.position ) {
			this.options.popup.setPosition(this.options.position);
			setTimeout(function () {
				self.options.popup.setMap(self.options.map);
				self.fitPopupToScreen();
				self.options.popup.show();
			},10);
		}
	};


	G5ERE_MAP.Marker.prototype.active = function() {
		var self = this;
		if ("object" === typeof (self.options.map)) {
			var $markerElement =  self.options.map.$element.find('#' + self.options.template.id);
			if ($markerElement.length > 0) {
				$markerElement.addClass('active');
			}
		}
		self.showPopup();
	};


	/*
	* G5ERE_MAP.Geocoder
	*/
	G5ERE_MAP.Geocoder = function () {
		this.init();
	};

	G5ERE_MAP.Geocoder.prototype.init = function () {
	};

	G5ERE_MAP.Geocoder.prototype.setMap = function (e) {
		this.map = e;
	};


	G5ERE_MAP.Geocoder.prototype.geocode = function (t, e, i) {
		var n = this,
			o = !1;
		if ("function" == typeof e) {
			i = e;
			e = {};
		}

		var s = {
			access_token: g5ere_map_config.accessToken,
			limit: 1,
		};

		if (g5ere_map_config.types.length) {
			s.types = g5ere_map_config.types.join(',');
		}

		if (g5ere_map_config.countries.length) {
			s.country = g5ere_map_config.countries.join(',');
		}
		e = $.extend(!0, {}, s, e);
		if (!encodeURIComponent(t).length) return i(o);
		$.get({
			url: "https://api.mapbox.com/geocoding/v5/mapbox.places/{query}.json".replace("{query}", encodeURIComponent(t)),
			data: e,
			dataType: "json",
			success: function (t, i) {
				"success" === i && t && t.features.length && (o = 1 !== e.limit ? t.features.map(n.formatFeature) : n.formatFeature(t.features[0]))
			},
			complete: function () {
				i(o);
			}
		})


	};

	G5ERE_MAP.Geocoder.prototype.formatFeature = function (e) {
		return {
			location: new G5ERE_MAP.LatLng(e.geometry.coordinates[1], e.geometry.coordinates[0]),
			latitude: e.geometry.coordinates[1],
			longitude: e.geometry.coordinates[0],
			address: e.place_name
		};
	};

	/**
	 * G5ERE_MAP.Autocomplete
	 * */
	G5ERE_MAP.Autocomplete = function (e) {
		this.init(e);
	};

	G5ERE_MAP.Autocomplete.prototype.init = function (e) {
		if (!(e instanceof Element)) return !1;
		var _that = this;
		this.element = e;
		this.$element = $(e);
		this.geocoder = new G5ERE_MAP.Geocoder();
		this.timeOutSearch = null;
		this.focusedItem = 0;
		this.hasQueried = !1;
		this.attachDropdown();
		this.$element.on('input', this.querySuggestions.bind(this));
		this.$element.on('focusin', this.showDropdown.bind(this));
		this.$element.on("focusout", this.hideDropdown.bind(this));
		this.$element.on("keydown click", this.navigateDropdown.bind(this));
		this.dropdown.on("click", ".g5ere__suggestion", function (t) {
			_that.selectItem($(this).index());
		});
	};

	G5ERE_MAP.Autocomplete.prototype.change = function (t) {
		this.$element.on('g5ere_autocomplete_change', function (e, p) {
			if (p) {
				t(p);
			} else {
				t(false);
			}
		});
	};

	G5ERE_MAP.Autocomplete.prototype.querySuggestions = function (t) {
		var _that = this;
		this.resetFocus();
		this.showDropdown();
		clearTimeout(this.timeOutSearch);
		this.timeOutSearch = setTimeout(function () {
			_that.geocoder.geocode(t.target.value, {
				limit: 5
			}, function (t) {
				if (this.hasQueried = !0, this.removeSuggestions(), !t) return !1;
				t.forEach(this.addSuggestion.bind(this));
			}.bind(_that));
		}, 300);
	};

	G5ERE_MAP.Autocomplete.prototype.navigateDropdown = function (t) {
		if (!this.hasQueried) {
			this.$element.trigger("input");
		}
		this.showDropdown();
		if (40 === t.keyCode) {
			this.focusedItem++;
			this.focusItem();
		}

		if (38 === t.keyCode) {
			this.focusedItem--;
			this.focusItem();
		}
		if (13 === t.keyCode) {
			t.preventDefault();
			if (this.focusedItem === 0) {
				this.focusedItem = 1;
			}
			this.selectItem(this.focusedItem - 1);
		}
	};


	G5ERE_MAP.Autocomplete.prototype.focusItem = function () {
		this.dropdown.find(".g5ere__suggestions-list .g5ere__suggestion").removeClass("active");
		var t = this.dropdown.find(".g5ere__suggestions-list .g5ere__suggestion");
		if (this.focusedItem < 0) {
			this.focusedItem = t.length;
		}
		if (this.focusedItem > t.length) {
			this.focusedItem = 0;
		}
		if (0 !== this.focusedItem) {
			this.dropdown.find(".g5ere__suggestions-list .g5ere__suggestion").eq(this.focusedItem - 1).addClass("active");
		}
	};

	G5ERE_MAP.Autocomplete.prototype.resetFocus = function () {
		this.focusedItem = 0;
		this.dropdown.find(".g5ere__suggestions-list .g5ere__suggestion").removeClass("active");
	};


	G5ERE_MAP.Autocomplete.prototype.showDropdown = function () {
		this.dropdown.addClass("active");
		var e = this.$element.get(0).getBoundingClientRect(),
			i = this.$element.offset();
		this.dropdown.css({
			top: i.top + e.height + "px",
			left: i.left + "px",
			width: e.width + "px"
		});
	};


	G5ERE_MAP.Autocomplete.prototype.hideDropdown = function () {
		this.dropdown.removeClass("active");
	};


	G5ERE_MAP.Autocomplete.prototype.selectItem = function (t) {
		var e = this.dropdown.find(".g5ere__suggestions-list .g5ere__suggestion").eq(t).data("place");
		this.$element.val(e.address);
		this.resetFocus();
		this.hideDropdown();
		this.$element.trigger('g5ere_autocomplete_change', [e]);
	};

	G5ERE_MAP.Autocomplete.prototype.attachDropdown = function () {
		this.dropdown = $('<div class="g5ere__autocomplete-dropdown"><div class="g5ere__suggestions-list"></div></div>');
		this.$element.attr('autocomplete', 'off');
		$('body').append(this.dropdown);
	};

	G5ERE_MAP.Autocomplete.prototype.removeSuggestions = function () {
		this.dropdown.find(".g5ere__suggestions-list").html('');
	};


	G5ERE_MAP.Autocomplete.prototype.addSuggestion = function (t) {
		var e = $(['<div class="g5ere__suggestion">', '<i class="fas fa-map-marker-alt"></i>', '<span class="g5ere__suggestion-address"></span>', "</div>"].join(""));
		e.data("place", t);
		e.find(".g5ere__suggestion-address").text(t.address);
		this.dropdown.find(".g5ere__suggestions-list").append(e);
	};

	/*
	* G5ERE_MAP.MAP
	*/
	G5ERE_MAP.MAP = function (element) {
		this.$element = $(element);
		this.element = element;
		this.init();
	};

	G5ERE_MAP.MAP.prototype.init = function () {
		this.options = $.extend({}, G5ERE_MAP.options, this.$element.data('options'));
		this.markers = [];
		this.bounds = new G5ERE_MAP.LatLngBounds;
		this.id = typeof (this.$element.attr('id')) !== 'undefined' ? this.$element.attr('id') : false;
		this.events = {
			zoom_changed: "zoomstart",
			bounds_changed: "moveend"
		};
		this.map = new mapboxgl.Map({
			container: this.element,
			zoom: parseInt(this.options.zoom, 10),
			minZoom: this.options.minZoom,
			interactive: this.options.draggable,
			style: G5ERE_MAP.getSkin(this.options.skin),
			scrollZoom: this.options.scrollwheel
		});
		if (this.options.navigationControl) {
			this.map.addControl(new mapboxgl.NavigationControl({
				showCompass: false
			}));
			this.map.addControl(new mapboxgl.FullscreenControl);
		}

		this.setCenter(new G5ERE_MAP.LatLng(0, 0));
		this.maybeAddMarkers();
		if (this.options.cluster_markers) {
			this.clusterer = new G5ERE_MAP.Clusterer(this);
			this.addListener('updating_markers', function () {
				this.clusterer.destroy();
			}.bind(this));
			this.addListener("updated_markers", function () {
				this.clusterer.load();
			}.bind(this));
			this.addListener('zoomend', this._updateCluster.bind(this));
			this.addListener('dragend', this._updateCluster.bind(this));
			this.addListener('resize', this._updateCluster.bind(this));
		}
		this.addListener("zoom_changed", this.closePopups.bind(this));
		this.addListener("click", this.closePopups.bind(this));
		this.addListener("refresh", this.refresh.bind(this));
		G5ERE_MAP.instances.push({
			id: this.id,
			map: this.map,
			instance: this
		});
	};

	G5ERE_MAP.MAP.prototype.maybeAddMarkers = function() {
		if (this.options._type === 'single-location') {
			var location = this.$element.data('location');
			if (location && location.position) {
				this.trigger('updating_markers');
				var position = new G5ERE_MAP.LatLng(location.position.lat, location.position.lng);
				var marker_option = {
					position: position,
					map: this,
					template: {
					}
				};

				if (location.marker) {
					marker_option.template.marker = location.marker;
					marker_option.template.id = location.id;
				}
				if (location.popup) {
					var content_popup = $('#g5ere__map_popup_template').html()
						.replace(/{{url}}/g, location.url)
						.replace("{{thumb}}", location.thumb)
						.replace("{{title}}", location.title)
						.replace("{{address}}", location.address);

					marker_option.popup = new G5ERE_MAP.Popup({
						content: content_popup
					});
				}

				var marker = new G5ERE_MAP.Marker(marker_option);
				this.markers.push(marker);
				this.setCenter(position);
				this.trigger("updated_markers");
			}
		}
	};

	G5ERE_MAP.MAP.prototype.setZoom = function (e) {
		this.map.setZoom(e);
	};

	G5ERE_MAP.MAP.prototype.resetZoom = function (e) {
		this.setZoom(this.options.zoom);
	};

	G5ERE_MAP.MAP.prototype.getZoom = function () {
		return Math.floor(this.map.getZoom());
	};

	G5ERE_MAP.MAP.prototype.setCenter = function (e) {
		this.map.setCenter(e.getSourceObject());
	};

	G5ERE_MAP.MAP.prototype.fitBounds = function (e) {
		if (!e.getSourceObject().isEmpty()) {
			this.map.fitBounds(e.getSourceObject(), {
				padding: 85,
				animate: !1
			});
		}
	};

	G5ERE_MAP.MAP.prototype.getBounds = function () {
		return this.map.getBounds();
	};


	G5ERE_MAP.MAP.prototype.panTo = function (e) {
		this.map.panTo(e.getSourceObject());
	};

	G5ERE_MAP.MAP.prototype.getClickPosition = function (e) {
		return new G5ERE_MAP.LatLng(e.lngLat.lat, e.lngLat.lng);
	};

	G5ERE_MAP.MAP.prototype.getDragPosition = function (e) {
		return new G5ERE_MAP.LatLng(e.target._lngLat.lat, e.target._lngLat.lng);
	};

	G5ERE_MAP.MAP.prototype.addListener = function (e, t) {
		this.map.on(this.getSourceEvent(e), function (e) {
			t(e);
		});
	};

	G5ERE_MAP.MAP.prototype.addListenerOnce = function (e, t) {
		this.map.once(this.getSourceEvent(e), function (e) {
			t(e);
		});
	};

	G5ERE_MAP.MAP.prototype.trigger = function (e) {
		this.map.fire(this.getSourceEvent(e));
	};


	G5ERE_MAP.MAP.prototype.getSourceObject = function () {
		return this.map;
	};

	G5ERE_MAP.MAP.prototype.getSourceEvent = function (e) {
		return void 0 !== this.events[e] ? this.events[e] : e;
	};

	G5ERE_MAP.MAP.prototype.closePopups = function () {
		for (var i = 0; i < this.markers.length; i++) {
			if ("object" === typeof (this.markers[i].options.popup)) {
				this.markers[i].options.popup.hide();
			}
		}
	};

	G5ERE_MAP.MAP.prototype.removeMarkers = function () {
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].remove();
		}
		this.markers.length = 0;
		this.markers = [];
	};

	G5ERE_MAP.MAP.prototype._updateCluster = function () {
		this.clusterer || (this.clusterer = new G5ERE_MAP.Clusterer(this));
		setTimeout(function () {
			this.clusterer.update();
		}.bind(this), 5);
	};

	G5ERE_MAP.MAP.prototype.refresh = function () {
		this.map.resize();
		if (this.options.cluster_markers ) {
			this.clusterer.update();
		}
	};

	G5ERE_MAP.MAP.prototype.activeMarker = function(id) {
		if (this.options.cluster_markers) {
			this.clusterer.update(100);
		}
		var self = this;
		clearTimeout(this.timeOutActive);
		this.timeOutActive = setTimeout(function () {
			for (var i = 0; i < self.markers.length; i++) {
				if (self.markers[i].options.template.id == id) {
					self.markers[i].active();
					break;
				}
			}
		},10);
	};

	G5ERE_MAP.MAP.prototype.deactiveMarker = function() {
		var self = this;
		if (self.options.cluster_markers) {
			self.clusterer.update();
		}
		self.$element.find('.g5ere__marker-container').removeClass('active');
		self.closePopups();
	};

	$(document).ready(function () {
		if (typeof (g5ere_map_config) !== 'undefined' && g5ere_map_config.accessToken !== '') {

			if (g5ere_map_config.skin === 'custom') {
				if (typeof g5ere_map_config.skin_custom === 'string' && g5ere_map_config.skin_custom.indexOf('mapbox://styles/') === 0) {
					G5ERE_MAP.skins.custom = g5ere_map_config.skin_custom;
				}
			}

			mapboxgl.accessToken = g5ere_map_config.accessToken;

			$('.g5ere__map-canvas:not(.manual)').each(function () {
				new G5ERE_MAP.MAP(this);
			});

			$(document).trigger("maps:loaded");
		}
	});
})(jQuery);