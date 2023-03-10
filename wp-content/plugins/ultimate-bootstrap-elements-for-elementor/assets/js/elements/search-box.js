(function ($) {
	'use strict';
	var UbeSearchBoxHandler = function ($scope, $) {
		var $search_box = $scope.find('.ube-search-box');
		var $s_show_modal = $scope.find('.ube-search-box-show-modal');
		var $s_modal = $scope.find('.ube-search-box-modal');
		var $s_modal_close = $scope.find('.ube-search-box-modal-close');

		$s_show_modal.click(function () {
			$s_modal.toggleClass('show');
			$search_box.find('.value-search').focus();
			setTimeout(function () {
				$search_box.find('.value-search').focus()
			}, 600);
		});
		$s_modal_close.click(function () {
			$s_modal.addClass('removing');
			setTimeout(function () {
				$s_modal.removeClass('removing');
				$s_modal.toggleClass('show');
				$search_box.find('.value-search').val('');
				if ($search_box.find('.ube-search-box-ajax-result').hasClass('in')) {
					$search_box.find('.ube-search-box-ajax-result').removeClass('in');
				}
			}, 600);
		});

		if ($search_box.hasClass('has_ajax_search')) {
			var $options = $search_box.data('search-options');
			var $btn_value_df = $search_box.find("button[type='submit']").html();
			$search_box.find('.value-search').keyup(function () {
				$search_box.find("button[type='submit']").html('<i class="fa fa-spinner fa-spin"></i>');
				$.ajax({
					url: $options.AjaxUrl,
					type: 'post',
					data: {
						action: 'ube_result_search_box',
						keyword: $search_box.find('.value-search').val(),
						data_post_per_page: $options.PostsPerPage,
						orderby: $options.orderby,
						order: $options.order,
						result_date: $options.ResultDate,
					},
					success: function (data) {
						$search_box.find("button[type='submit']").html($btn_value_df);
						$search_box.find('.ube-search-box-ajax-result').addClass('in');
						if ($search_box.find('.value-search').val() === '' && $search_box.find('.ube-search-box-ajax-result').hasClass('in')) {
							$search_box.find('.ube-search-box-ajax-result').removeClass('in');
						}
						$search_box.find('.ube-search-box-ajax-result').html(data);
					}
				});
			});
		}
	};

	 window.addEventListener( 'elementor/frontend/init', () => {
		elementorFrontend.hooks.addAction('frontend/element_ready/ube-search-box.default', UbeSearchBoxHandler);
	});
})(jQuery);