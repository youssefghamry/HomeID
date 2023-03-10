(function ($) {
		'use strict';

		var UbeChartHandler = function ($scope, $) {
			var $chartEl = $scope.find('.ube-chart'),
				chatType = $chartEl.data('chart-type'),
				options = $chartEl.data('chart-options'),
				labels = $chartEl.data('chart-labels'),
				datasets = $chartEl.data('chart-datasets'),
				more_setting = $chartEl.data('chart-additional-options'),
				chartId = more_setting.chatId,
				$ctx = $scope.find('#' + chartId),
				defaultOptions = {
					maintainAspectRatio: false,
				};
			options = $.extend(true, defaultOptions, options);
			var myChart = new Chart($ctx, {
				type: chatType,
				data: {
					labels: labels,
					datasets: datasets
				},
				options: options
			});
		};
		 window.addEventListener( 'elementor/frontend/init', () => {
			elementorFrontend.hooks.addAction('frontend/element_ready/ube-chart.default', UbeChartHandler);
		});


	}
)(jQuery);