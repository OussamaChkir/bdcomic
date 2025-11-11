/**
 * Book Grid Block JavaScript
 * Handles tab switching and AJAX loading for the book grid component
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initBookGrid();
    });

    /**
     * Initialize book grid functionality
     */
    function initBookGrid() {
        // Initialize Bootstrap tabs if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            var triggerTabList = [].slice.call(document.querySelectorAll('#bookGridTabs button[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        }

        // Handle tab switching
        $('#bookGridTabs button[data-bs-toggle="tab"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('bs-target');
            var tabId = target.replace('#', '');
            
            // Update active states
            $('#bookGridTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            
            $('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
            
            // Load content if needed (for future AJAX implementation)
            loadTabContent(tabId);
        });

        // Handle book actions
        $(document).on('click', '.book-quick-action', function(e) {
            e.preventDefault();
            handleBookAction($(this));
        });

        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    /**
     * Load tab content (for future AJAX implementation)
     */
    function loadTabContent(tabId) {
        // This function can be extended to load content via AJAX
        // For now, content is loaded statically
        console.log('Loading content for tab:', tabId);
    }

    /**
     * Handle book action buttons (wishlist, read, etc.)
     */
    function handleBookAction($button) {
        var postId = $button.data('post-id');
        var listType = $button.data('list-type');
        var postType = $button.data('post-type') || 'livre';
        var isActive = $button.hasClass('active');
        var action = isActive ? 'remove' : 'add';

        // Show loading state
        $button.prop('disabled', true);
        var originalContent = $button.html();
        $button.html('<span class="dashicons dashicons-update"></span>');

        // Prepare data
        var data = {
            action: action === 'add' ? 'add_to_user_books' : 'remove_from_user_books',
            post_id: postId,
            list_type: listType,
            post_type: postType,
            nonce: bookGridData.nonce
        };

        // Make AJAX request
        $.ajax({
            url: bookGridData.ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    // Toggle active state
                    $button.toggleClass('active');
                    
                    // Update icon and tooltip
                    updateButtonState($button, listType, !isActive);
                    
                    // Show success message (optional)
                    showNotification('Action effectuée avec succès', 'success');
                    
                    // Update tab counts if needed
                    updateTabCounts();
                } else {
                    showNotification(response.data || 'Une erreur est survenue', 'error');
                    $button.html(originalContent);
                }
            },
            error: function() {
                showNotification(bookGridData.strings.error, 'error');
                $button.html(originalContent);
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    }

    /**
     * Update button state after action
     */
    function updateButtonState($button, listType, isActive) {
        var $icon = $button.find('.dashicons');
        var $text = $button.find('.btn-text');
        
        // Update icon
        $icon.removeClass();
        $icon.addClass('dashicons');
        
        if (isActive) {
            switch (listType) {
                case 'wishlist':
                    $icon.addClass('dashicons-heart-filled');
                    break;
                case 'read':
                    $icon.addClass('dashicons-yes');
                    break;
				case 'owned':
					$icon.addClass('dashicons-archive');
					break;
                case 'collection_wishlist':
                    $icon.addClass('dashicons-star-filled');
                    break;
                case 'missing_albums':
                    $icon.addClass('dashicons-minus');
                    break;
            }
        } else {
            switch (listType) {
                case 'wishlist':
                    $icon.addClass('dashicons-heart');
                    break;
                case 'read':
                    $icon.addClass('dashicons-yes-alt');
                    break;
				case 'owned':
					$icon.addClass('dashicons-archive');
					break;
                case 'collection_wishlist':
                    $icon.addClass('dashicons-star-empty');
                    break;
                case 'missing_albums':
                    $icon.addClass('dashicons-plus');
                    break;
            }
        }
        
        // Update tooltip
        var newTitle = isActive ? 
            getRemoveText(listType) : 
            getAddText(listType);
        $button.attr('title', newTitle);
    }

    /**
     * Get add text for list type
     */
    function getAddText(listType) {
        switch (listType) {
            case 'wishlist':
                return 'Ajouter aux souhaits';
            case 'read':
                return 'Marquer comme lu';
			case 'owned':
				return 'Marquer comme possédé';
            case 'collection_wishlist':
                return 'Ajouter aux souhaits de collection';
            case 'missing_albums':
                return 'Ajouter aux albums manquants';
            default:
                return 'Ajouter';
        }
    }

    /**
     * Get remove text for list type
     */
    function getRemoveText(listType) {
        switch (listType) {
            case 'wishlist':
                return 'Retirer des souhaits';
            case 'read':
                return 'Marquer comme non lu';
			case 'owned':
				return 'Marquer comme non possédé';
            case 'collection_wishlist':
                return 'Retirer des souhaits de collection';
            case 'missing_albums':
                return 'Retirer des albums manquants';
            default:
                return 'Retirer';
        }
    }

    /**
     * Update tab counts (for future implementation)
     */
    function updateTabCounts() {
        // This can be implemented to update tab badges with counts
        console.log('Updating tab counts...');
    }

    /**
     * Show notification message
     */
    function showNotification(message, type) {
        // Create notification element
        var $notification = $('<div class="book-grid-notification ' + type + '">' + message + '</div>');
        
        // Add to page
        $('body').append($notification);
        
        // Show notification
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        // Hide notification after 3 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Refresh tab content
     */
    function refreshTabContent(tabId) {
        var $tabPane = $('#' + tabId);
        var $booksGrid = $tabPane.find('.books-grid');
        
        // Show loading state
        $booksGrid.addClass('loading');
        
        // Simulate loading (replace with actual AJAX call)
        setTimeout(function() {
            $booksGrid.removeClass('loading');
            // Reload content here
        }, 1000);
    }

    // Expose functions globally if needed
    window.BookGrid = {
        refreshTabContent: refreshTabContent,
        loadTabContent: loadTabContent
    };

})(jQuery);

// Add notification styles
jQuery(document).ready(function($) {
    if (!$('#book-grid-notification-styles').length) {
        $('head').append(`
            <style id="book-grid-notification-styles">
                .book-grid-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 6px;
                    color: white;
                    font-weight: 500;
                    z-index: 9999;
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                    max-width: 300px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }
                .book-grid-notification.show {
                    transform: translateX(0);
                }
                .book-grid-notification.success {
                    background: #28a745;
                }
                .book-grid-notification.error {
                    background: #dc3545;
                }
                .book-grid-notification.info {
                    background: #17a2b8;
                }
            </style>
        `);
    }
});
