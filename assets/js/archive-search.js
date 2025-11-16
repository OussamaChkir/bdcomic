jQuery(document).ready(function($) {
    'use strict';

    // Archive search functionality
    class ArchiveSearch {
        constructor() {
            this.searchInput = $('.archive-search-input');
            this.filterSelects = $('.archive-filter-select');
            this.resultsContainer = $('.archive-results-container');
            this.loadingSpinner = $('.archive-loading');
            this.noResultsMessage = $('.archive-no-results');
            this.paginationContainer = $('.archive-pagination');
            this.currentPage = 1;
            this.searchTimeout = null;
            this.postType = this.getPostType();
            
            this.init();
        }

        getPostType() {
            // Get post type from body class or URL
            const bodyClasses = $('body').attr('class');
            if (bodyClasses.includes('post-type-archive-artiste')) return 'artiste';
            if (bodyClasses.includes('post-type-archive-collection')) return 'collection';
            if (bodyClasses.includes('post-type-archive-editeur')) return 'editeur';
            if (bodyClasses.includes('post-type-archive-livre')) return 'livre';
            if (bodyClasses.includes('post-type-archive-guide_lecture')) return 'guide_lecture';
            return 'post';
        }

        init() {
            this.bindEvents();
            this.initAutocomplete();
        }

        bindEvents() {
            // Search input with debouncing
            this.searchInput.on('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.currentPage = 1;
                    this.performSearch();
                }, 500);
            });

            // Filter selects
            this.filterSelects.on('change', () => {
                this.currentPage = 1;
                this.performSearch();
            });

            // Pagination
            $(document).on('click', '.archive-pagination a', (e) => {
                e.preventDefault();
                const page = $(e.target).data('page');
                if (page) {
                    this.currentPage = page;
                    this.performSearch();
                }
            });

            // Clear search
            $(document).on('click', '.archive-clear-search', (e) => {
                e.preventDefault();
                this.clearSearch();
            });
        }

        initAutocomplete() {
            // Simple autocomplete implementation without jQuery UI dependency
            if (this.searchInput.length) {
                this.searchInput.on('input', (e) => {
                    const term = $(e.target).val();
                    if (term.length >= 2) {
                        this.showAutocompleteSuggestions(term);
                    } else {
                        this.hideAutocompleteSuggestions();
                    }
                });

                // Hide suggestions when clicking outside
                $(document).on('click', (e) => {
                    if (!$(e.target).closest('.archive-search-container').length) {
                        this.hideAutocompleteSuggestions();
                    }
                });
            }
        }

        showAutocompleteSuggestions(term) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'archive_autocomplete',
                    term: term,
                    post_type: this.postType,
                    nonce: archiveSearchData.nonce
                },
                success: (data) => {
                    if (data.success && data.data.length > 0) {
                        this.displayAutocompleteSuggestions(data.data);
                    } else {
                        this.hideAutocompleteSuggestions();
                    }
                },
                error: () => {
                    this.hideAutocompleteSuggestions();
                }
            });
        }

        displayAutocompleteSuggestions(suggestions) {
            let $suggestionsContainer = $('.archive-autocomplete-suggestions');
            
            if (!$suggestionsContainer.length) {
                $suggestionsContainer = $('<div class="archive-autocomplete-suggestions"></div>');
                this.searchInput.after($suggestionsContainer);
            }

            const suggestionsHtml = suggestions.map(suggestion => 
                `<div class="autocomplete-suggestion" data-value="${suggestion.value}">${suggestion.label}</div>`
            ).join('');

            $suggestionsContainer.html(suggestionsHtml).show();

            // Handle suggestion clicks
            $suggestionsContainer.off('click', '.autocomplete-suggestion').on('click', '.autocomplete-suggestion', (e) => {
                const value = $(e.target).data('value');
                this.searchInput.val(value);
                this.hideAutocompleteSuggestions();
                this.currentPage = 1;
                this.performSearch();
            });
        }

        hideAutocompleteSuggestions() {
            $('.archive-autocomplete-suggestions').hide();
        }

        performSearch() {
            const searchData = this.getSearchData();
            
            this.showLoading();
            this.hideResults();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'archive_search',
                    search: searchData,
                    post_type: this.postType,
                    page: this.currentPage,
                    nonce: archiveSearchData.nonce
                },
                success: (response) => {
                    this.hideLoading();
                    if (response.success) {
                        this.displayResults(response.data);
                    } else {
                        this.showError(response.data);
                    }
                },
                error: (xhr, status, error) => {
                    this.hideLoading();
                    this.showError('Une erreur est survenue lors de la recherche.');
                }
            });
        }

        getSearchData() {
            const data = {
                search_term: this.searchInput.val(),
                filters: {}
            };

            this.filterSelects.each((index, element) => {
                const $select = $(element);
                const filterName = $select.data('filter');
                const filterValue = $select.val();
                
                if (filterValue && filterValue !== '') {
                    data.filters[filterName] = filterValue;
                }
            });

            return data;
        }

        displayResults(data) {
            if (data.html) {
                this.resultsContainer.html(data.html);
                this.showResults();
                
                if (data.pagination) {
                    this.paginationContainer.html(data.pagination);
                }
                
                if (data.total_results === 0) {
                    this.showNoResults();
                } else {
                    this.hideNoResults();
                }
            }
        }

        clearSearch() {
            this.searchInput.val('');
            this.filterSelects.val('');
            this.currentPage = 1;
            this.performSearch();
        }

        showLoading() {
            this.loadingSpinner.show();
        }

        hideLoading() {
            this.loadingSpinner.hide();
        }

        showResults() {
            this.resultsContainer.show();
        }

        hideResults() {
            this.resultsContainer.hide();
        }

        showNoResults() {
            this.noResultsMessage.show();
        }

        hideNoResults() {
            this.noResultsMessage.hide();
        }

        showError(message) {
            this.resultsContainer.html(`<div class="alert alert-danger">${message}</div>`);
            this.showResults();
        }
    }

    // Initialize archive search if we're on an archive page
    if ($('.archive-search-container').length) {
        new ArchiveSearch();
    }
});
