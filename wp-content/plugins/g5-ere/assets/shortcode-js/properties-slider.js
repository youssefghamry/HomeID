var G5ERE_SC_PROPERTIES_SLIDER = G5ERE_SC_PROPERTIES_SLIDER || {};
(function ($) {
	"use strict";
	G5ERE_SC_PROPERTIES_SLIDER = {
		init: function ($wrap) {
			if (typeof $wrap === "undefined") {
				$wrap = $('body');
			}
			var self = this;
			this.setHeight($wrap);
			$(window).resize(function () {
				setTimeout(function () {
					self.setHeight($wrap);
				},10);

			});
		},
		isBreakpoint : function (breakpoint) {
			return window.matchMedia('(max-width: ' + (breakpoint) + 'px)').matches;
		},
		getSettings: function (options) {
			var settings = {
				'height_mode' : options.height_mode,
				'height' : options.height,
			};
			for (var i in options.responsive) {
				if (this.isBreakpoint(options.responsive[i]['breakpoint'] - 1) ) {
					settings = options.responsive[i]['settings'];
				}
			}
			return settings;
		},
		setHeight: function ($wrap) {
			if (typeof $wrap === "undefined") {
				$wrap = $('body');
			}
			var self = this;
			$('[data-toggle="g5ere__psh"]',$wrap).each(function () {
				if ($(this).hasClass('slick-initialized')) {
					$(this).slick('unslick');
					$(this).find('.slick-initialized').slick('unslick');
				}

				$(this).find('.g5ere__psi-inner').css('min-height', '');
				var id = $(this).attr('id'),
					options = $(this).data('g5ere__psh-options'),
					settings = self.getSettings(options),
					height = '';
				if (settings.height_mode === 'custom') {
					height = settings.height;
				} else {
					var windowHeight = $(window).height(),
						offsetTop = $(this).offset().top;
					if (offsetTop < windowHeight) {
						height = (100 - offsetTop / (windowHeight / 100)) + 'vh';
					}
				}

				$(this).find('.g5ere__psi-inner').css('min-height', height);
				$(this).addClass('slick-slider');
				$(this).find('[data-slick-options]').addClass('slick-slider');
				G5CORE.util.slickSlider();
			});
		},

	};

	$(document).ready(function () {
		G5ERE_SC_PROPERTIES_SLIDER.init();
	});
})(jQuery);