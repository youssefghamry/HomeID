var G5Element_VC = G5Element_VC || {};
(function ($) {
    "use strict";
    G5Element_VC = {
        init : function(){
           this.template_tab();
           this.selectColorChangeStyle();
        },
        template_tab : function () {
            $('.g5element-templates-cat-wrap li','.vc_panel-tabs').on('click',function(event) {
                event.preventDefault();
                var $this = $(this),
                    filter = $this.data('filter');
                if ($this.hasClass('active')) return;
                $('.g5element-templates-cat-wrap li','.vc_panel-tabs').removeClass('active');
                $this.addClass('active');
                $(filter,'.g5element-templates-wrap').removeClass('hidden');
                $('.g5element-template-item:not('+ filter+')','.g5element-templates-wrap').addClass('hidden');
            });


            $('.g5element-templates-cat-wrap li','.vc_panel-tabs').each(function () {
                var $this = $(this),
                    filter = $this.data('filter'),
                    count = $(filter,'.g5element-templates-wrap').length;
                if (filter === '*') {
                    count = $('.g5element-template-item').length;
                }
                $this.append('<span class="g5element-template-count">'+ count +'</span>');
            });
        },
	    selectColorChangeStyle: function () {
		    $(document).on('change', '.gel-colored-dropdown', function () {
			    var $selectField = $(this),
				    color_name = $selectField.val();

			    if ((color_name === '') || (color_name === 'custom')) {
				    $selectField.css('background-color', '');
				    $selectField.css('color', '');
			    }
			    else {
				    $selectField.css('background-color', $selectField.find('option.' + color_name).css('background-color'));
				    $selectField.css('color', $selectField.find('option.' + color_name).css('color'));
			    }
		    });
	    }
    };
    $(document).ready(function () {
        G5Element_VC.init();

        if (typeof (window.VcColumnView) === "undefined") return;

        window.G5ElementLayoutSectionView = window.VcColumnView.extend({
	        events:{
		        'click > .vc_controls [data-vc-control="add"],.gel-layout-section-controls > .gel-layout-section-add':"addElement",
		        "click > .wpb_element_wrapper > .gel-layout-section-body > .vc_empty-container": "addToEmpty",
		        //'click > .vc_controls [data-vc-control="edit"],.gel-layout-section-controls > .gel-layout-section-edit':"editElement",
	        },
            render: function() {
	            window.G5ElementLayoutSectionView.__super__.render.call(this);
	            this.replaceTemplateVars();
	            return this;
            },
	        replaceTemplateVars: function() {
		        var title, $panelHeading, $title;
		        title = this.model.getParam("title");
		        $panelHeading = this.$el.find(".gel-layout-section-heading");
		        $title = $panelHeading.find('.title');

		        if (_.isEmpty(title)) {
                    title = $panelHeading.data('default-title');
                }
		        var template = vc.template($title.html(), vc.templateOptions.custom);
		        $title.html(template({section_title: title }));
            },
	        setContent: function() {
		        this.$content = this.$el.find("> .wpb_element_wrapper > .gel-layout-section-body > .vc_container_for_children");
	        }
        });

	    window.G5ElementLayoutContainerView = window.VcColumnView.extend({
		    events:{
			    'click > .gel-layout-container-custom-event,.vc_controls [data-vc-control="delete"]':"deleteShortcode",
			    'click > .gel-layout-container-custom-event,.vc_controls [data-vc-control="clone"]':"clone",
			    'click > .gel-layout-container-custom-event,.vc_controls [data-vc-control="edit"]':"editElement",
		    },
		    setContent: function() {
			    this.$content = this.$el.find("> .wpb_element_wrapper > .gel-layout-container-body > .vc_container_for_children");
		    }
	    });

	    window.G5ElementSliderContainerView = window.VcColumnView.extend({
		    events:{
			    'click > .vc_controls [data-vc-control="delete"], .gel-slider-container-controls [data-vc-control="delete"]': "deleteShortcode",
			    'click > .vc_controls [data-vc-control="add"], .gel-slider-container-controls [data-vc-control="add"]': "addElement",
			    'click > .vc_controls [data-vc-control="edit"], .gel-slider-container-controls [data-vc-control="edit"]': "editElement",
			    'click > .vc_controls [data-vc-control="clone"], .gel-slider-container-controls [data-vc-control="clone"]': "clone",
			    "click > .wpb_element_wrapper > .gel-slider-container-body > .vc_empty-container": "addToEmpty",
		    },
		    setContent: function() {
			    this.$content = this.$el.find("> .wpb_element_wrapper > .gel-slider-container-body > .vc_container_for_children");
		    }
	    });
    });
})(jQuery);
