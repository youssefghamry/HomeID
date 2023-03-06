/**
 * select field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SelectClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SelectClass.prototype = {
		init: function() {
		},
		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		}
	};
})(jQuery);