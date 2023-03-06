(function ($) {
	"use strict";
	var G5Element_Icon_Box = {
		init: function () {
			$('.gel-icon-svg').each(function() {
				var self = $(this);
				var $svg_id = self.data('id');
				var $settings = self.data('vivus');
				var data_stroke = self.data("stroke");
				var data_stroke_hover = self.data("stroke-hover");
				var $wrap = self.closest('.gel-icon-box');
				var options = {
					type: $settings.type,
					duration: $settings.duration,
					animTimingFunction: Vivus.EASE_OUT,
					onReady: function (myVivus) {
						myVivus.el.setAttribute('color',data_stroke);
						var c=myVivus.el.childNodes;
						for (var i = 0; i < c.length; i++) {
							var child = c[i];
							var pchildern = child.children;
							if (pchildern != undefined) {
								$(c[i]).attr("stroke", "currentColor");
								for (var j = 0; j < pchildern.length; j++) {
									$(pchildern[j]).attr("stroke", "currentColor");
								}
							}
						}
						if (data_stroke_hover!='') {
							$wrap.hover(function () {
								myVivus.el.setAttribute("color", data_stroke_hover);
							}, function () {
								myVivus.el.setAttribute('color',data_stroke);
							});
						}
					}
				};
				self.css("opacity","1");
				if ($svg_id !== '') {
					var vivus = new Vivus($svg_id, options);
				}
				if ('show' === $settings.play_on_hover) {
					$wrap.hover(function () {
						vivus.stop()
							.reset()
							.play(2);
					}, function () {
						//vivus.finish();
					});
				}
			});
		},
	};
	$(window).load(function() {
		G5Element_Icon_Box.init();
	});
})(jQuery);