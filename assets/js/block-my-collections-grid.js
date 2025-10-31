/**
 * My Collections Grid JavaScript
 * Handles search, filtering, and interactions for the collections grid
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
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
        $searchInput.on('input', debounce(function() {
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
        $searchClear.on('click', function() {
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
        
        $filters.on('change', function() {
            filterCollections();
        });
    }

    function initViewFilters() {
        const $filterButtons = $('.filter-btn');
        
        $filterButtons.on('click', function() {
            $filterButtons.removeClass('active');
            $(this).addClass('active');
            filterCollections();
        });
    }

    function initBookInteractions() {
        // Book hover effects
        $('.book-item').on('mouseenter', function() {
            $(this).addClass('hovered');
        }).on('mouseleave', function() {
            $(this).removeClass('hovered');
        });
        
        // Collection toggle (if needed for future expansion)
        $('.collection-header').on('click', function() {
            const $collection = $(this).closest('.collection-group');
            const $books = $collection.find('.collection-books');
            
            if ($books.is(':visible')) {
                $books.slideUp(300);
                $(this).addClass('collapsed');
            } else {
                $books.slideDown(300);
                $(this).removeClass('collapsed');
            }
        });
    }

    function filterCollections() {
        const searchTerm = $('#collections-search').val().toLowerCase().trim();
        const maisonFilter = $('#filter-maison').val();
        const collectionFilter = $('#filter-collection').val();
        const artisteFilter = $('#filter-artiste').val();
        const dateFilter = $('#filter-date').val();
        const viewFilter = $('.filter-btn.active').data('filter');
        
        let visibleCollections = 0;
        
        $('.collection-group').each(function() {
            const $collection = $(this);
            let showCollection = true;
            
            // Search filter
            if (searchTerm) {
                const collectionName = $collection.find('.collection-name').text().toLowerCase();
                const bookTitles = $collection.find('.book-title').text().toLowerCase();
                const bookVolumes = $collection.find('.book-volume').text().toLowerCase();
                
                if (collectionName.indexOf(searchTerm) === -1 && 
                    bookTitles.indexOf(searchTerm) === -1 && 
                    bookVolumes.indexOf(searchTerm) === -1) {
                    showCollection = false;
                }
            }
            
            // Collection filter
            if (collectionFilter && $collection.data('collection-id') != collectionFilter) {
                showCollection = false;
            }
            
            // View filter
            if (viewFilter !== 'all') {
                let hasMatchingBooks = false;
                $collection.find('.book-item').each(function() {
                    const $book = $(this);
                    const isRead = $book.data('read') == 1;
                    const isLoaned = $book.data('loaned') == 1;
                    
                    if (viewFilter === 'unread' && !isRead) {
                        hasMatchingBooks = true;
                        return false; // break
                    } else if (viewFilter === 'loaned' && isLoaned) {
                        hasMatchingBooks = true;
                        return false; // break
                    }
                });
                
                if (!hasMatchingBooks) {
                    showCollection = false;
                }
            }
            
            // Show/hide collection with animation
            if (showCollection) {
                $collection.removeClass('hidden').show();
                visibleCollections++;
            } else {
                $collection.addClass('hidden');
                setTimeout(() => {
                    if ($collection.hasClass('hidden')) {
                        $collection.hide();
                    }
                }, 300);
            }
        });
        
        // Update empty state
        updateEmptyState(visibleCollections);
        
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
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
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
