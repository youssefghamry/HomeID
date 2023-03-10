(function ($) {
    'use strict';
    var UbeFancytext = function ($scope) {

        initHeadline($scope.find('.ube-fancy-text'));
    };
     window.addEventListener( 'elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/ube-fancy-text.default', UbeFancytext);
    });


    function initHeadline($headlines) {

        var options_default = {
            animationDelay: 4500,
            barAnimationDelay: 3800,
            typingSpeed: 50,
            typingDelay: 2500,
            typingLoop: false,
            typingCursor: false,
        };

        $headlines.each(function () {
            var $this = $(this);
            var options = $this.data('additional-options');
            var animationDelay = options.animationDelay;
            options = $.extend({}, options_default, options);
            options.barAnimationDelay = options.animationDelay;

            if (options.animationDelay < 3000) {
                options.barWaiting = options.animationDelay * (10 / 100);
            }
            if (options.animationDelay >= 3000) {
                options.barWaiting = options.animationDelay - 3000;
            }

            var duration = animationDelay;

            if ($this.hasClass('loading-bar')) {
                duration = options.barAnimationDelay;
                setTimeout(function () {
                    $this.find('.ube-fancy-text-animated').addClass('is-loading')
                }, options.barWaiting);
            }

            if ($this.hasClass('ube-fancy-text-typing')) {
                var txt = $this.data('text');
                $this.find('.ube-fancy-text-animated').typed({
                    strings: txt,
                    typeSpeed: options.typingSpeed,
                    backSpeed: 0,
                    startDelay: 300,
                    backDelay: options.typingDelay,
                    showCursor: options.typingCursor,
                    loop: options.typingLoop,
                });
            } else {
                //trigger animation
                setTimeout(function () {
                    hideWord($this.find('.ube-fancy-text-show').eq(0), options);
                }, duration);
            }
        });
    }

    function hideWord($word, options) {

        var nextWord = takeNext($word);
        if ($word.parents('.ube-fancy-text').hasClass('ube-fancy-text-loading')) {
            $word.parent('.ube-fancy-text-animated').removeClass('is-loading');
            switchWord($word, nextWord);
            setTimeout(function () {
                hideWord(nextWord, options)
            }, options.barAnimationDelay);
            setTimeout(function () {
                $word.parent('.ube-fancy-text-animated').addClass('is-loading')
            }, options.barWaiting);

        } else {
            switchWord($word, nextWord);
            setTimeout(function () {
                hideWord(nextWord, options)
            }, options.animationDelay);
        }
    }

    function takeNext($word) {
        return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
    }

    function switchWord($oldWord, $newWord) {
        $oldWord.removeClass('ube-fancy-text-show').addClass('ube-fancy-text-hidden');
        $newWord.removeClass('ube-fancy-text-hidden').addClass('ube-fancy-text-show');
    }

})(jQuery);

