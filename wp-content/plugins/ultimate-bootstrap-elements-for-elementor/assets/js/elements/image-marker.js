(function ($) {
	'use strict';

	var UbeImageMarkerHandler = function ($scope, $) {
		$scope.find('.ube-image-pointer').each(function () {
			var configs = {
				container: $(this).parent(),
				html: true,
				placement: 'top',
				offset: 20,
				delay: {"show": 0, "hide": 100},
			};
			$(this).tooltip(configs);
		});
	};
	 window.addEventListener( 'elementor/frontend/init', () => {
		elementorFrontend.hooks.addAction('frontend/element_ready/ube-image-marker.default', UbeImageMarkerHandler);
	});


})(jQuery);