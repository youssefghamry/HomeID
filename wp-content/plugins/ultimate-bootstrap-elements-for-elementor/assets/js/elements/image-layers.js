(function ($) {
		'use strict';

		var UbeImageLayersHandler = function ($scope, $) {
			var $image_wrapper = $scope.find(".layers-wrapper");
			var $id = $image_wrapper.data('id');
			if($id!==undefined){
				var $image_layers = $scope.find('#' + $id)[0];
				var parallaxInstance = new Parallax($image_layers, {
					relativeInput: true,
					hoverOnly: true,
					selector:'.layer'

				});
			}
		};
		 window.addEventListener( 'elementor/frontend/init', () => {
			elementorFrontend.hooks.addAction('frontend/element_ready/ube-image-layers.default', UbeImageLayersHandler);
		});


	}
)(jQuery);