(function ($) {
	"use strict";
	var G5Element_Google_Map_Container = {
		init: function () {
			$(".gel-google-map").each(function () {
				var gmMapDiv = $(this);
				if (gmMapDiv.length) {
					gmMapDiv.gmap3({
						action: "init",
						marker: {
							values: gmMapDiv.data('marker'),
							events: {
								click: function (marker, event, context) {
									if (context.data == 0) {
										return;
									}
									var map = $(this).gmap3("get");
									var infowindow = $(this).gmap3({get: {name: "infowindow"}});
									if (infowindow) {
										infowindow.open(map, marker);
										infowindow.setContent(context.data);
									} else {
										$(this).gmap3({
											infowindow: {
												anchor: marker,
												options: {content: context.data}
											}
										});
									}
								}
								,
								callback: function (m) { //m will be  the array of markers
									var bounds = new google.maps.LatLngBounds();
									for (var i = 0; i < m.length; ++i) {
										bounds.extend(m[i].getPosition());
									}
									try {
										var map = $(this).gmap3({action: 'get'});
										map.fitBounds(bounds);
										map.setCenter(bounds.getCenter())
									} catch (e) {
									}
								}
							}
						},
						overlay: {
							values: gmMapDiv.data('overlay'),
							events: {
								click: function (marker, event, context) {
									if (context.data == 0) {
										return;
									}
									var map = $(this).gmap3("get");
									var infowindow = $(this).gmap3({get: {name: "infowindow"}});
									if (infowindow) {
										infowindow.open(map, marker);
										infowindow.setContent(context.data);
									} else {
										$(this).gmap3({
											infowindow: {
												anchor: marker,
												options: {content: context.data}
											}
										});
									}
								}
								,
								callback: function (m) { //m will be the array of markers
									var bounds = new google.maps.LatLngBounds();
									for (var i = 0; i < m.length; ++i) {
										bounds.extend(m[i].getPosition());
									}
									try {
										var map = $(this).gmap3({action: 'get'});
										map.fitBounds(bounds);
										map.setCenter(bounds.getCenter())
									} catch (e) {
									}
								}
							}
						},
						map: {
							options: {
								zoom: parseInt(gmMapDiv.data('map-zoom')),
								scrollwheel: gmMapDiv.data('zoom-mouse-wheel'),
								mapTypeId: google.maps.MapTypeId.ROADMAP,
								zoomControl: true,
								mapTypeControl: false,
								scaleControl: false,
								streetViewControl: false,
								draggable: true,
								styles: gmMapDiv.data('map-style'),
							}
						}
					})
				}
			});
		}
	};
	$(document).ready(function () {
		G5Element_Google_Map_Container.init();
	});
})(jQuery);
