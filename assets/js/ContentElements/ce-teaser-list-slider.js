document.addEventListener('DOMContentLoaded', function () {
    teaser_list_slider();
});

let resizeTimeout;
window.addEventListener('resize', function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(teaser_list_slider, 200);
});

function teaser_list_slider() {
    $('.teaser-list-slider').each(function () {
        var $slider = $(this);

        if (!$slider.hasClass('slick-initialized')) {
            var $wrapper = $slider.closest('.teaser-slider-wrapper');

            $slider.slick({
                dots: true,
                arrows: true,
                appendDots: $wrapper.find('.teaser-slider-dots'),
                appendArrows: $wrapper.find('.teaser-slider-nav'),
                infinite: false,
                slidesToShow: 1,
                adaptiveHeight: true,
            });
        }
    });
}