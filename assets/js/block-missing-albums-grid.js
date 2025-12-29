/**
 * Missing Albums Grid JavaScript
 * Handles collapsible headers for the missing albums grid
 */

(function ($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function () {
        initMissingAlbumsGrid();
    });

    function initMissingAlbumsGrid() {
        const $grid = $('.missing-albums-grid');

        if ($grid.length === 0) {
            return;
        }

        // Initialize collection toggle
        initCollectionToggle();
    }

    function initCollectionToggle() {
        $('.missing-albums-grid .collection-header').on('click', function (e) {
            // Prevent triggering if clicking a link inside the header
            if ($(e.target).closest('a').length) {
                return;
            }

            const $header = $(this);
            const $collection = $header.closest('.collection-group');
            const $books = $collection.find('.collection-books');

            if ($books.is(':visible')) {
                $books.slideUp(300);
                $header.addClass('collapsed');
            } else {
                $books.slideDown(300);
                $header.removeClass('collapsed');
            }
        });
    }

})(jQuery);
