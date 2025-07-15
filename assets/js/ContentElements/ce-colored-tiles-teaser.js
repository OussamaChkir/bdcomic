jQuery(document).ready(function($) {
    function adjustModalBodyWidth() {
        $('.modal-body').each(function() {
            var $modalBody = $(this);
            var viewportHeight = $(window).height();
            var bodyHeight = $modalBody.outerHeight();

            if (bodyHeight > viewportHeight) {
                $modalBody.css('width', '100%');
            } else {
                $modalBody.css('width', '');
            }
        });
    }

    $('.modal').on('shown.bs.modal', function () {
        adjustModalBodyWidth();
    });

    $(window).on('resize', function () {
        adjustModalBodyWidth();
    });
});