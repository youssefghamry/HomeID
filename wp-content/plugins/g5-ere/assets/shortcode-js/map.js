var G5ERE_MAP_SHORT_CODE = G5ERE_MAP_SHORT_CODE || {};
(function ($) {
	"use strict";
	var $window = $(window),
		$body = $('body'),
		isRTL = $body.hasClass('rtl');

	G5ERE_MAP_SHORT_CODE.SHORT_CODE_MAP = {
		initMap: function (id) {
			this.updateMap(id);
		},
		updateMap: function (id) {
			var t = G5ERE_MAP.getInstance(id);
			var map = t.instance;
			var self = this;
			if (map) {
				map.trigger("updating_markers");
				var markers = map.$element.data('marker');
				if (markers) {
					$.each(markers, function () {
						var latLngArr = this.address.split(',');
						var lat = latLngArr['0'];
						var lng = latLngArr['1'];
						var marker_content = this.data;
						if (this.options.type === 'image' && this.options.html !== '') {

							var marker_option = {
								position: new G5ERE_MAP.LatLng(lat, lng),
								map: map,
								template: {
									marker: {
										type: 'image',
										html: '<img src="' + this.options.html + '">'
									}
								}
							};

						} else {
							marker_option = {
								position: new G5ERE_MAP.LatLng(lat, lng),
								map: map,
							};
						}


						if (marker_content !== '0') {
							marker_option.popup = new G5ERE_MAP.Popup({
								content: marker_content
							});
						}
						var marker = new G5ERE_MAP.Marker(marker_option);
						map.markers.push(marker);
						map.bounds.extend(marker.getPosition());

					});

				}
				if (map.markers.length < 1) {
					map.setZoom(2);
					map.setCenter(new G5ERE_MAP.LatLng(33.9, 27.8));
				}

				if (map.markers.length === 1) {
					map.setCenter(map.markers[0].getPosition());
				}

				if (map.markers.length > 1) {
					map.fitBounds(map.bounds);
				}

				map.trigger("updated_markers");
			}
		},
	};
	$(document).on('maps:loaded', function () {
		var maps = $('.g5ere__map-canvas');
		$.each(maps, function () {
			G5ERE_MAP_SHORT_CODE.SHORT_CODE_MAP.initMap(this.id);
		});
	});
})(jQuery);