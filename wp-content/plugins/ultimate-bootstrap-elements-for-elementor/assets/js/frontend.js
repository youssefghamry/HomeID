var UBE = UBE || {};
(function ($) {
	"use strict";

	UBE = {};
	UBE.animation = {
		doAnimations: function (elements, visible) {
			var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			elements.each(function () {
				var $this = $(this);
				var $animationType = 'animated ' + $this.data('animation');
				$this.css({
					'animation-delay': 400,
					'-webkit-animation-delay': 400
				});
				if (visible === true) {
					$(this).css({
						'visibility': 'visible'
					});
				}
				$this.addClass($animationType).one(animationEndEvents, function () {
					$this.removeClass($animationType);
				});
			});
		}
	};
})(jQuery);