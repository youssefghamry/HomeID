var G5Element = G5Element || {};
(function ($) {
	var $body = $('body'),
		$window = $(window),
		$siteWrapper = $('#site-wrapper'),
		$document = $(document),
		changeMediaResponsive = false,
		beforeMedia = '';

    "use strict";
    G5Element = {
        init : function(){
           this.fullWidth();
           this.parallax();
           this.resize();
           this.fullHeightCallBack();
		   this.updatePageTitle();


        },
        fullWidth : function () {
	        var window_width = $siteWrapper.outerWidth();

            $('[data-g5element-full-width="true"]').each(function () {
	            G5Element.fullWidthOne($(this));
            });
        },
	    fullWidthOne: function($elm) {
		    if ($body.hasClass('has-sidebar') && ($elm.closest('#primary-content').length)) {
			    return;
		    }
		    var window_width = $siteWrapper.outerWidth();

		    $elm.css({
			    'position': '',
			    'width': '',
			    'left': '',
			    'right': '',
			    'paddingLeft': '',
			    'paddingRight': '',
		    });
		    var this_width = $elm.outerWidth(),
			    left = ((window_width - this_width) / 2),
			    position = $elm.css('position') === 'static' ? 'relative' : $elm.css('position');

		    var cssData = {
			    'position': position,
			    'width': window_width + 'px',
			    'left': -left + 'px',
			    'right': 'auto',
		    };

		    if ($elm.data('g5element-stretch-content')) {
			    if ($elm.hasClass('vc_row-no-padding')) {
				    cssData['paddingLeft'] = '0';
				    cssData['paddingRight'] = '0';
			    }
		    }
		    else {
			    cssData['paddingLeft'] = left + 'px';
			    cssData['paddingRight'] = left + 'px';
		    }
		    $elm.css(cssData);
		    $elm.trigger('g5element_after_stretch_content');
	    },
        parallax: function() {
            $('.g5element-bg-vparallax').each(function () {
                $(this).parallax('50%', $(this).data('g5element-parallax-speed'))
            });
        },
        resize: function () {
            $window.on('resize', function () {
	            G5Element.fullWidth();
	            setTimeout(function () {
		            G5Element.fullWidth();
	            }, 20);
            });
        },
	    fullHeightCallBack: function () {
		    $document.on('vc-full-height-row', function (event, elm) {
			    G5Element.fullWidthOne($(elm));
		    });
	    },
		updatePageTitle: function () {
            $body.on('g5core_pagination_ajax_before_update_page_title', function (event, _data, $ajaxHTML, target, loadMore) {
                $('[data-g5element-full-width="true"]',$('.g5core-page-title')).each(function () {
                    G5Element.fullWidthOne($(this));
                });

            });
        }
    };
    $(document).ready(function () {
        G5Element.init();
    });

})(jQuery);
