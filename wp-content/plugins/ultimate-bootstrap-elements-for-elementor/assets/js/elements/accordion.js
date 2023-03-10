(function ($) {
	'use strict';

	var UbeAdvAccordionHandler = function ($scope, $) {
		var $acc = $scope.find('.ube-accordion');
		$acc.find('.ube-collapsible').on('show.bs.collapse', function () {
			var $id = $(this).attr('id');
			var $parent = $(this).parents('.ube-accordion');
			var $current = $parent.find('[data-target="#' + $id + '"]') || $parent.find('[href="#' + $id + '"]');
			if (!$acc.hasClass('accordion-toggle')) {
				$parent.find('.ube-accordion-link').removeClass('active');
				$parent.find('.ube-accordion-card').removeClass('active');
			}
			if ($parent.find('.slick-slider').length > 0) {
				$parent.find('.slick-slider').slick('refresh');
			}
			$current.toggleClass('active');
			$current.parents('.ube-accordion-card').addClass('active');
		});

		$acc.find('.ube-collapsible').on('hidden.bs.collapse', function () {
			var $id = $(this).attr('id');
			var $parent = $(this).parents('.ube-accordion');
			var $current = $parent.find('[data-target="#' + $id + '"]') || $parent.find('[href="#' + $id + '"]');
			$current.removeClass('active');
			$current.parents('.ube-accordion-card').removeClass('active');
		});
	};
	 window.addEventListener( 'elementor/frontend/init', () => {
		elementorFrontend.hooks.addAction('frontend/element_ready/ube-advanced-accordion.default', UbeAdvAccordionHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/ube-accordion.default', UbeAdvAccordionHandler);
	});

})(jQuery);