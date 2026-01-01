/**
 * Hero Slider Widget JavaScript
 */
(function($) {
    'use strict';

    window.initHeroSlider = function(widgetId, bgImages, slideCount) {
        var currentSlide = 0;
        var previousSlide = 0;
        var forward = true;
        var swiper;

        // Create background images
        var bgHTML = bgImages.map(function(url) { 
            return '<img src="' + url + '"/>'; 
        }).join('');
        $('.hero-slider-' + widgetId + ' .as-slider-background').html(bgHTML);

        // Set first image as active
        $('.hero-slider-' + widgetId + ' .as-slider-background img').eq(0).addClass('currentForward');

        // Create navigation dots
        var dotsHTML = '';
        for (var i = 0; i < slideCount; i++) {
            dotsHTML += '<span class="dot' + (i === 0 ? ' active' : '') + '">' +
                       '<span class="dot-number">' + (i + 1) + '</span></span>';
        }
        $('.hero-slider-' + widgetId + ' .as-bar').append(dotsHTML);

        // Initialize Swiper
        swiper = new Swiper('.food-slider-' + widgetId, {
            slidesPerView: 5,
            spaceBetween: 30,
            centeredSlides: true,
            loop: false,
            navigation: {
                nextEl: '.nav-next-' + widgetId,
                prevEl: '.nav-prev-' + widgetId,
            },
            on: {
                slideChange: function() {
                    var activeIndex = this.activeIndex;
                    updateSlide(activeIndex);
                },
                init: function() {
                    updateSlide(0);
                }
            },
            breakpoints: {
                0: { slidesPerView: 1.3, spaceBetween: 15 },
                768: { slidesPerView: 3, spaceBetween: 20 },
                1024: { slidesPerView: 5, spaceBetween: 30 },
            }
        });

        function updateSlide(index) {
            currentSlide = index;
            updateBackgroundImage();
            updateTextContent(index);
            updateDots(index);
            previousSlide = currentSlide;
        }

        function updateBackgroundImage() {
            var images = $('.hero-slider-' + widgetId + ' .as-slider-background img');
            images.removeClass('currentForward currentBackward prev');

            if (previousSlide < currentSlide) {
                forward = true;
            } else if (previousSlide > currentSlide) {
                forward = false;
            }

            images.eq(previousSlide).addClass('prev');

            if (forward) {
                images.eq(currentSlide).addClass('currentForward');
            } else {
                images.eq(currentSlide).addClass('currentBackward');
            }
        }

        function updateTextContent(index) {
            var titleItems = $('.hero-slider-' + widgetId + ' .titles-widget .title-item');
            var descItems = $('.hero-slider-' + widgetId + ' .desc-widget .desc-item');
            var btnItems = $('.hero-slider-' + widgetId + ' .button-widget .btn');

            titleItems.removeClass('active').hide();
            descItems.removeClass('active').hide();
            btnItems.hide();

            titleItems.eq(index).addClass('active').show();
            descItems.eq(index).addClass('active').show();
            btnItems.eq(index).show();

            var titleHeight = titleItems.eq(index).outerHeight(true);
            var descHeight = descItems.eq(index).outerHeight(true);
            var btnHeight = btnItems.eq(index).outerHeight(true);

            $('.hero-slider-' + widgetId + ' .titles-widget').css('height', titleHeight + 'px');
            $('.hero-slider-' + widgetId + ' .desc-widget').css('height', descHeight + 'px');
            $('.hero-slider-' + widgetId + ' .button-widget').css('height', btnHeight + 'px');
        }

        function updateDots(index) {
            $('.hero-slider-' + widgetId + ' .dot').removeClass('active');
            $('.hero-slider-' + widgetId + ' .dot').eq(index).addClass('active');
        }

        // Dot click event
        $(document).on('click', '.hero-slider-' + widgetId + ' .dot', function() {
            var index = $(this).index('.hero-slider-' + widgetId + ' .dot');
            swiper.slideTo(index);
        });

        // Image click event
        $(document).on('click', '.hero-slider-' + widgetId + ' .swiper-slide', function(e) {
            var slideIndex = $(this).attr('data-index');
            if (slideIndex !== undefined) {
                swiper.slideTo(parseInt(slideIndex));
            }
        });

        // Set initial content height
        updateTextContent(0);

        // Add loaded class after delay
        setTimeout(function() {
            $('.hero-slider-' + widgetId).addClass('loaded');
        }, 300);
    };

})(jQuery);
