document.addEventListener('DOMContentLoaded', function() {
    teaser_list_slider();
});

window.addEventListener('resize', function() {
    teaser_list_slider();
});

function teaser_list_slider() {
    var $slider = $('.teaser-list-slider');

    if ($slider.length && !$slider.hasClass('slick-initialized')) {
        $slider.slick({
            dots: true,
            arrows: true,
            appendDots: $slider.closest('.teaser-slider-wrapper').find('.teaser-slider-dots'),
            appendArrows: $slider.closest('.teaser-slider-wrapper').find('.teaser-slider-nav'),
            infinite: false,
            slidesToShow: 1,
            adaptiveHeight: true,
        });
    }
}