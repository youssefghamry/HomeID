(function($){
	$(document).ready(function () {
		$('.ere-insert-shortcode-button').on('click',function(){
			ERE_POPUP.required_element();
			ERE_POPUP.reset_fileds();
			G5Utils.popup.show({
				target: '#ere-input-shortcode-wrap',
				type: 'target',
				callback: function () {

				}
			});
		});
	});
})(jQuery);
