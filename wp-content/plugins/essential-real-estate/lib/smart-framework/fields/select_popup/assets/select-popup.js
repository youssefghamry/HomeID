/**
 * select popup field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_Select_popupClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_Select_popupClass.prototype = {
		_$popup: [],

		init: function() {
			var that = this,
				template = wp.template('gsf-select-popup');

			that.$container.find('.gsf-field-select_popup-info > .info-select').on('click', function () {
				$('#gsf-popup-select-wrapper').remove();

				var items = $(this).data('items');
				if (!items) {
					items = 1;
				}
				var html = template({
					options: $(this).data('options'),
					items: items,
					popup_width: $(this).data('popup-width'),
					title: $(this).data('title')
				});

				var $popup = $(html),
					$popupListing = $popup.find('.gsf-popup-select-listing');

				$('body').append($popup);

				that._$popup = $popup;

				$popup.find('.gsf-popup-select-item').on('click', function () {
					var $this = $(this),
						$img = $this.find('img');

					if (!$this.hasClass('active')) {
                        that.$container.find('input[data-field-control]').val($this.data('value'));
                        that.$container.find('.gsf-field-select_popup-preview').attr('src',$img.data('thumb'));
                        that.$container.find('.gsf-field-select_popup-info > .info-name').text($img.attr('alt'));
                        that.$container.find('input[data-field-control]').trigger('change');
					}

					G5Utils.popup.close();
				});

				that.open(that, that.$container.find('input[data-field-control]').val());
			});
		},

		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		},
		open: function (that, currentValue) {
			G5Utils.popup.show({
				type: 'target',
				target: '#gsf-popup-select-target',
				callback: function ($container) {
					$container.find('.gsf-popup-select-item[data-value="' + currentValue + '"]').addClass('active');
				}
			});
		}
	};
})(jQuery);