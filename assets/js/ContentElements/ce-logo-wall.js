jQuery(document).ready(function($) {
    $('.block-logo-wall').each(function() {
        var $block = $(this);

        $block.find('.load-more-logos').on('click', function(e) {
            e.preventDefault();
            $block.find('.hidden-logo').slideDown().removeClass('hidden-logo');
            $(this).hide();
        });
    });
});