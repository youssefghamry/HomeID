(function ($) {
	'use strict';
	var UbePostMasonryHandler = function ($scope, $) {
		var masonry_elem = $scope.find('.ube-entry-post-thumb').eq(0);

		masonry_elem.imagesLoaded(function () {
			setTimeout(function () {
				$('.ube-posts-masonry').isotope({
					itemSelector: '.ube-post-grid-item',
					percentPosition: true,
					transitionDuration: 1500,
					masonry: {
						columnWidth: '.ube-post-grid-item',
					},
				});
			}, 500);

		});


	};
	 window.addEventListener( 'elementor/frontend/init', () => {
		elementorFrontend.hooks.addAction('frontend/element_ready/ube-post-masonry.default', UbePostMasonryHandler);
	});

})(jQuery);

