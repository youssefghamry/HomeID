/**
 * Created by Administrator on 5/4/2017.
 */
var GSF_Fonts = GSF_Fonts || {};
(function($) {
    "use strict";
    GSF_Fonts = {
        _loadFontCount: 0,
        _fonts: {},
        _isSubmitting: false,
        _icon_spin: 'gsf-icon-spinner',
        init: function () {
            this.tabClick();
            this.binderFonts();
            this.searchFonts();
            this.events();
        },
        events : function() {
            $('.gsf-reset-active-fonts').on('click',function(event){
                event.preventDefault();
                if (!confirm(GSF_META_DATA.msgConfirmResetFont)) {
                    return;
                }
                if (GSF_Fonts._isSubmitting) {
                    return;
                }
                GSF_Fonts._isSubmitting = true;
                var $this = $(this);
                $this.find('i').addClass(GSF_Fonts._icon_spin);
                $.ajax({
                    url: GSF_META_DATA.ajaxUrl,
                    data: {
                        action: 'gsf_reset_active_font',
                        _nonce: GSF_META_DATA.nonce
                    },
                    type: 'post',
                    success: function (res) {
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        GSF_Fonts._isSubmitting = false;
                        if (res.success) {
                            GSF_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        GSF_Fonts._isSubmitting = false;
                    }
                });
            });

            $(document).on('click', '.gsf-font-item-change-font', function (event) {
                event.preventDefault();

                var $this = $(this),
                    fontType = $this.data('type'),
                    familyName = $this.closest('.gsf-font-item,.gsf-font-active-item').data('name'),
                    fontObj = GSF_Fonts.findFontSource(GSF_Fonts._fonts[fontType], familyName);

                if (fontObj == null) {
                    return;
                }

                var template = wp.template('gsf-popup-change-font');

                $('body').append(template(GSF_Fonts._fonts['active']));

                $('.gsf-popup-change-font').find('.gsf-popup-close').on('click', function () {
                    $('.gsf-popup-wrap').remove();
                });

                $('.gsf-popup-change-font').find('.gsf-change-font-item button').on('click', function () {
                    var msgConfirm = $(this).closest('.gsf-popup-change-font').data('msg-confirm'),
                        fromFont = $(this).data('name');
                    msgConfirm = msgConfirm.replace('{1}', fromFont);
                    msgConfirm = msgConfirm.replace('{2}', familyName);
                    if (!confirm(msgConfirm)) {
                        return;
                    }

                    $('.gsf-popup-wrap').remove();

                    if (GSF_Fonts._isSubmitting) {
                        return true;
                    }
                    GSF_Fonts._isSubmitting = true;

                    $this.find('i').addClass(GSF_Fonts._icon_spin);

                    $.ajax({
                        url: GSF_META_DATA.ajaxUrl,
                        data: {
                            action: 'gsf_change_font',
                            _nonce: GSF_META_DATA.nonce,
                            font_data: fontObj,
                            from_font: fromFont,
                            to_font: familyName
                        },
                        type: 'post',
                        success: function (res) {
                            GSF_Fonts._isSubmitting = false;
                            $this.find('i').removeClass(GSF_Fonts._icon_spin);
                            if (res.success) {
                                $this.closest('.gsf-font-item')
                                    .find('.gsf-font-item-action-add')
                                    .find('i').attr('class', 'dashicons dashicons-yes');
                                GSF_Fonts.bindActiveFont();
                            }
                            else {
                                alert(res.data);
                            }
                        },
                        error: function () {
                            GSF_Fonts._isSubmitting = false;
                            $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        }
                    });
                });
            });

        },
        binderFonts: function () {
            var fontTypes = ['google', 'standard', 'custom'];
            for (var i in fontTypes) {
                this.bindFonts(fontTypes[i]);
            }
            this.bindActiveFont();
        },
        getFontFamily: function (name) {
            if (name.indexOf(',') != -1) {
                return name;
            }
            if (name.indexOf(' ') != -1) {
                return "'" + name + "'";
            }
            return name;
        },
        enqueueFont: function (font) {
            var url = '';
            switch (font.kind) {
                case 'webfonts#webfont': {
                    url = 'https://fonts.googleapis.com/css?family=' + font.family.replace(' ', '+') + ':100,200,300,400,500,600,700,800,900,1000';
                    break;
                }
                case 'custom': {
                    url = typeof font.css_url !== 'undefined' ? font.css_url :   GSF_META_DATA.font_url + font.css_file;
                    break;
                }
            }
            if (url !== '') {
                $('body').append('<link class="gsf-preview-css-font" rel="stylesheet" href="' + url +  '" type="text/css" media="all" />');
            }
        },
        bindFonts: function (fontType, isShow) {
            if (isShow == null) {
                isShow = false;
            }
            var _nonce = $('.gsf-fonts-wrapper').data('nonce');
            $.ajax({
                url: GSF_META_DATA.ajaxUrl,
                data: {
                    action: 'gsf_get_font_list',
                    _nonce: GSF_META_DATA.nonce,
                    font_type: fontType
                },
                type: 'get',
                success: function (res) {
                    if (!res.success) {
                        return;
                    }
                    GSF_Fonts._fonts[res.data.font_type] = res.data.fonts.items;
                    var template;
                    switch (res.data.font_type) {
                        case 'google': {
                            template = wp.template('gsf-google-fonts');
                            break;
                        }
                        case 'standard': {
                            template = wp.template('gsf-standard-fonts');
                            break;
                        }
                        case 'custom': {
                            template = wp.template('gsf-custom-fonts');
                            break;
                        }
                    }

                    if (template) {
                        var $listing = $('.gsf-font-listing-inner'),
                            $element = $(template(res.data));
                        $('#' + fontType + '_fonts').remove();
                        $listing.append($element);
                        GSF_Fonts.addEventListener($element);
                        $element.find('.gsf-font-categories li a').first().trigger('click');
                        if (isShow) {
                            $element.show();
                        }
                    }
                    GSF_Fonts._loadFontCount++;
                },
                error: function () {
                    GSF_Fonts._loadFontCount++;
                }
            });
        },
        addEventListener: function ($container) {
            $container.find('form').ajaxForm({
                beforeSubmit: function() {
                    if (GSF_Fonts._isSubmitting) {
                        return false;
                    }
                    G5Utils.loadingButton.show($('.gsf-custom-font-popup form button'));
                    GSF_Fonts._isSubmitting = true;
                },
                success: function (res) {
                    G5Utils.loadingButton.hide($('.gsf-custom-font-popup form button'));
                    GSF_Fonts._isSubmitting = false;
                    if (res.success) {
                        GSF_Fonts.bindFonts('custom', true);
                        G5Utils.popup.close();
                    }
                    else {
                        alert(res.data);
                    }
                }
            });

            $container.find('.gsf-font-categories li a').on('click', function () {
                var $this = $(this),
                    cate = $this.parent().data('ref');
                $container.find('.gsf-font-categories li').removeClass('active');
                $this.parent().addClass('active');
                GSF_Fonts.filterFontsByCate($container, cate);
                $('#search_fonts').val('');
            });

            $container.find('.gsf-add-custom-font button').on('click', function () {
                G5Utils.popup.show({
                    type: 'target',
                    target: '#gsf-custom-font-popup',
                    callback: function ($pcontent) {
                        $pcontent.find('form')[0].reset();
                    }
                });
            });

            $container.find('.gsf-font-item-action-delete').on('click', function (event) {
                event.preventDefault();
                if (!confirm(GSF_META_DATA.msgConfirmDeleteCustomFont)) {
                    return;
                }
                if (GSF_Fonts._isSubmitting) {
                    return;
                }
                GSF_Fonts._isSubmitting = true;
                var $this = $(this),
                    familyName = $this.closest('.gsf-font-item').data('name');
                $this.find('i').addClass(GSF_Fonts._icon_spin);
                $.ajax({
                    url: GSF_META_DATA.ajaxUrl,
                    data: {
                        action: 'gsf_delete_custom_font',
                        _nonce: GSF_META_DATA.nonce,
                        family_name: familyName
                    },
                    type: 'post',
                    success: function (res) {
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        GSF_Fonts._isSubmitting = false;
                        if (res.success) {
                            GSF_Fonts.bindFonts('custom', true);
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        GSF_Fonts._isSubmitting = false;
                    }
                });
            });

            $container.find('.gsf-font-item-action-add').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    fontType = $this.data('type'),
                    familyName = $this.closest('.gsf-font-item').data('name'),
                    fontObj = GSF_Fonts.findFontSource(GSF_Fonts._fonts[fontType], familyName);

                if (fontObj == null) {
                    return;
                }
                if ($this.find('i').hasClass('dashicons-yes')) {
                    return;
                }
                if (GSF_Fonts._isSubmitting) {
                    return true;
                }
                GSF_Fonts._isSubmitting = true;

                $this.find('i').addClass(GSF_Fonts._icon_spin);

                $.ajax({
                    url: GSF_META_DATA.ajaxUrl,
                    data: {
                        action: 'gsf_using_font',
                        _nonce: GSF_META_DATA.nonce,
                        font_data: fontObj
                    },
                    type: 'post',
                    success: function (res) {
                        GSF_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        if (res.success) {
                            $this.find('i').attr('class', 'dashicons dashicons-yes');
                            GSF_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        GSF_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                    }
                });
            });



        },
        bindActiveFont: function () {
            var _nonce = $('.gsf-fonts-wrapper').data('nonce');
            $.ajax({
                url: GSF_META_DATA.ajaxUrl,
                data: {
                    action: 'gsf_get_font_list',
                    _nonce: GSF_META_DATA.nonce,
                    font_type: 'active'
                },
                type: 'get',
                success: function (res) {
                    if (!res.success) {
                        return;
                    }
                    GSF_Fonts._fonts[res.data.font_type] = res.data.fonts.items;
                    var template = wp.template('gsf-active-fonts');

                    if (template) {
                        var $listing = $('.gsf-font-active-listing'),
                            $element = $(template(res.data));
                        $('#active_fonts').remove();
                        $listing.append($element);
                        GSF_Fonts.activeFontAddEventListener($element);
                        $('.gsf-preview-css-font').remove();
                        for (var i in res.data.fonts.items) {
                            GSF_Fonts.enqueueFont(res.data.fonts.items[i]);
                        }
                    }
                },
                error: function () {
                }
            });
        },
        activeFontAddEventListener: function ($container) {
            $container.find('.gsf-font-active-item-header').on('click', function (event) {
                if ($(event.target).closest('.gsf-font-active-item-remove,.gsf-font-item-change-font').length) {
                    return;
                }
                $(this).toggleClass('in');
                $(this).next('.gsf-font-active-content').slideToggle();
            });

            $container.find('.gsf-font-active-item-remove').on('click', function (event) {
                event.preventDefault();
                if (!confirm(GSF_META_DATA.msgConfirmRemoveActiveFont)) {
                    return;
                }

                var $this = $(this),
                    $item = $this.closest('.gsf-font-active-item'),
                    familyName = $item.data('name');

                if (GSF_Fonts._isSubmitting) {
                    return true;
                }
                GSF_Fonts._isSubmitting = true;
                $this.find('i').addClass(GSF_Fonts._icon_spin);

                $.ajax({
                    url: GSF_META_DATA.ajaxUrl,
                    data: {
                        action: 'gsf_remove_active_font',
                        _nonce: GSF_META_DATA.nonce,
                        family_name: familyName
                    },
                    type: 'post',
                    success: function (res) {
                        GSF_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                        if (res.success) {
                            $('.gsf-font-item[data-name="' + res.data.family + '"]').find('.gsf-font-item-action-add i').attr('class', 'dashicons dashicons-plus-alt2');
                            GSF_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        GSF_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(GSF_Fonts._icon_spin);
                    }
                });
            });
            $container.find('form').ajaxForm({
                beforeSubmit: function() {
                    if (GSF_Fonts._isSubmitting) {
                        return false;
                    }
                    $container.find('form').find('button i').addClass(GSF_Fonts._icon_spin);
                    GSF_Fonts._isSubmitting = true;
                },
                success: function (res) {
                    $container.find('form').find('button i').removeClass(GSF_Fonts._icon_spin);
                    GSF_Fonts._isSubmitting = false;
                    if (!res.success) {
                        alert(res.data);
                    }
                }
            });


        },

        findFontSource: function (sources, name) {
            for (var i in sources) {
                if (sources[i].family == name) {
                    return sources[i];
                }
            }
            return null;
        },
        filterFontsByCate: function ($container, cate) {
            var $items = $container.find('.gsf-font-item');
            $items.each(function(){
                var $this = $(this);
                if ($this.data('category') !== cate) {
                    $this.hide();
                } else {
                    $this.show();
                }
            });
        },
        filterFontByKeyWord: function ($container, keyword) {
            var $items = $container.find('.gsf-font-item');
            $items.each(function(){
                var $this = $(this),
                    name = $this.find('.gsf-font-item-name').text();

                try {
                    if (name.search(new RegExp(keyword, "i")) < 0) {
                        $this.hide();
                    } else {
                        $this.show();
                    }
                }
                catch (ex)  {}
            });
        },
        searchFonts: function () {
            $('#search_fonts').on('keyup', function (event) {
                var $container = $('.gsf-font-container:visible'),
                    keyword = $(this).val();

                $container.find('.gsf-font-categories li').removeClass('active');
                GSF_Fonts.filterFontByKeyWord($container, keyword);
            });
        },
        tabClick: function () {
            $('.gsf-font-type > li > a').on('click', function (event) {
                event.preventDefault();
                if (GSF_Fonts._loadFontCount < 3) {
                    return;
                }
                var $this = $(this);
                if ($this.parent().hasClass('active')) {
                    return;
                }
                var ref = $(this).data('ref');

                $('.gsf-font-type > li').removeClass('active');
                $this.parent().addClass('active');

                $('.gsf-font-container').each(function() {
                    var $container = $(this);
                    if (($container.data('ref') != ref)) {
                        $container.slideUp();
                    }
                });
                $('#' + ref + '_fonts').slideDown(function() {
                    if ($('#' + ref + '_fonts').find('.gsf-font-categories li a').length) {
                        $('#' + ref + '_fonts').find('.gsf-font-categories li a').first().trigger('click');
                    }
                    else {
                        $('#search_fonts').val('');
                        $('#search_fonts').trigger('keyup');
                    }
                });
            });
        }
    }
    $(document).ready(function () {
        GSF_Fonts.init();
    });
})(jQuery);