/**
 * My Collections Grid JavaScript
 * Handles search, filtering, and interactions for the collections grid
 */

(function ($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function () {
        initCollectionsGrid();
    });

    function initCollectionsGrid() {
        const $grid = $('.my-collections-grid');

        if ($grid.length === 0) {
            return;
        }

        // Initialize search functionality
        initSearch();

        // Initialize filters
        initFilters();

        // Initialize view filters
        initViewFilters();

        // Initialize book interactions
        initBookInteractions();
    }

    function initSearch() {
        const $searchInput = $('#collections-search');
        const $searchClear = $('.search-clear');

        // Search input handler
        $searchInput.on('input', debounce(function () {
            const searchTerm = $(this).val().toLowerCase().trim();
            filterCollections();

            // Show/hide clear button
            if (searchTerm) {
                $searchClear.show();
            } else {
                $searchClear.hide();
            }
        }, 300));

        // Clear search
        $searchClear.on('click', function () {
            $searchInput.val('');
            $searchClear.hide();
            filterCollections();
        });

        // Show clear button if there's existing text
        if ($searchInput.val()) {
            $searchClear.show();
        }
    }

    function initFilters() {
        const $filters = $('.filter-select, .filter-date');

        $filters.on('change', function () {
            filterCollections();
        });
    }

    function initViewFilters() {
        const $filterButtons = $('.filter-btn');

        $filterButtons.on('click', function () {
            $filterButtons.removeClass('active');
            $(this).addClass('active');
            filterCollections();
        });
    }

    function initBookInteractions() {
        // Book hover effects
        $('.book-item').on('mouseenter', function () {
            $(this).addClass('hovered');
        }).on('mouseleave', function () {
            $(this).removeClass('hovered');
        });


        // Collection toggle
        $('.collection-header').on('click', function (e) {
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

    function filterCollections() {
        var searchTerm = $('#collections-search').val().toLowerCase().trim();
        var viewFilter = $('.filter-btn.active').data('filter');

        // Hide empty message initially
        $('.collections-empty').hide();

        var totalVisibleBooks = 0;

        $('.collection-group').each(function () {
            var $collection = $(this);
            var collectionName = $collection.find('.collection-name').text().toLowerCase();
            var $books = $collection.find('.book-item');
            var visibleBooksInCollection = 0;

            $books.each(function () {
                var $book = $(this);

                // 1. Check View Filter
                var isRead = $book.attr('data-read') === '1';
                var isLoaned = $book.attr('data-loaned') === '1';
                var isOwned = $book.attr('data-owned') === '1';

                var matchesView = false;
                if (viewFilter === 'owned') {
                    matchesView = isOwned;
                } else if (viewFilter === 'read') {
                    matchesView = isRead;
                } else if (viewFilter === 'loaned') {
                    matchesView = isLoaned;
                } else {
                    // Default fallback
                    matchesView = true;
                }

                // 2. Check Search Filter
                var matchesSearch = true;
                if (searchTerm) {
                    var bookTitle = $book.find('.book-title').text().toLowerCase();
                    var bookVolume = $book.find('.book-volume').text().toLowerCase();

                    var bookMatches = bookTitle.indexOf(searchTerm) > -1 || bookVolume.indexOf(searchTerm) > -1;
                    var collectionMatches = collectionName.indexOf(searchTerm) > -1;

                    if (!bookMatches && !collectionMatches) {
                        matchesSearch = false;
                    }
                }

                // Show/Hide book
                if (matchesView && matchesSearch) {
                    $book.show();
                    visibleBooksInCollection++;
                } else {
                    $book.hide();
                }
            });

            // Show/Hide collection
            if (visibleBooksInCollection > 0) {
                $collection.removeClass('hidden').show();

                // Also ensure books container is visible if it was toggled
                var $booksContainer = $collection.find('.collection-books');
                if ($booksContainer.is(':hidden') && !$collection.find('.collection-header').hasClass('collapsed')) {
                    $booksContainer.show();
                }
            } else {
                $collection.addClass('hidden').hide();
            }

            totalVisibleBooks += visibleBooksInCollection;
        });

        // Update empty state
        updateEmptyState(totalVisibleBooks);

        // Update statistics if needed
        updateFilteredStatistics();
    }

    function updateEmptyState(visibleCollections) {
        const $emptyState = $('.collections-empty');

        if (visibleCollections === 0) {
            $emptyState.show();
        } else {
            $emptyState.hide();
        }
    }

    function updateFilteredStatistics() {
        // This could be expanded to show filtered statistics
        // For now, we'll keep the original statistics
    }

    // Utility function for debouncing
    function debounce(func, wait, immediate) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            const later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Export functions for potential external use
    window.CollectionsGrid = {
        filterCollections: filterCollections,
        initCollectionsGrid: initCollectionsGrid
    };

})(jQuery);
