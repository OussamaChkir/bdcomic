jQuery(document).ready(function($) {
    'use strict';

    // Whislisted Book Grid functionality
    class WhislistedBookGrid {
        constructor() {
            this.searchInput = $('#whislisted-book-search-input');
            this.clearSearchBtn = $('#clear-search');
            this.booksGrid = $('#whislisted-books-grid');
            this.searchTimeout = null;

            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Search input with debouncing
            if (this.searchInput.length) {
                this.searchInput.on('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.performSearch();
                    }, 500);
                });
            }

            // Clear search
            if (this.clearSearchBtn.length) {
                this.clearSearchBtn.on('click', (e) => {
                    e.preventDefault();
                    this.clearSearch();
                });
            }
        }

        performSearch() {
            const searchTerm = this.searchInput.val().trim();

            // Show/hide clear button
            if (searchTerm.length > 0) {
                this.clearSearchBtn.show();
            } else {
                this.clearSearchBtn.hide();
            }

            this.showLoading();

            $.ajax({
                url: whislistedBookGridData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'search_user_wishlist',
                    search_term: searchTerm,
                    nonce: whislistedBookGridData.nonce
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

        displayResults(data) {
            if (data.html) {
                this.booksGrid.html(data.html);
            } else {
                this.booksGrid.html('<div class="no-books-message"><p>Aucun livre trouvé pour cette recherche.</p></div>');
            }
        }

        clearSearch() {
            this.searchInput.val('');
            this.clearSearchBtn.hide();
            this.performSearch();
        }

        showLoading() {
            this.booksGrid.addClass('loading');
        }

        hideLoading() {
            this.booksGrid.removeClass('loading');
        }

        showError(message) {
            this.booksGrid.html(`<div class="alert alert-danger">${message}</div>`);
        }
    }

    // Initialize if the grid exists
    if ($('#whislisted-books-grid').length) {
        new WhislistedBookGrid();
    }
});
