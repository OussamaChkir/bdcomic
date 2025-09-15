/**
 * User Books Management JavaScript
 * Handles AJAX interactions for user book collections
 */

jQuery(document).ready(function($) {
    'use strict';

    // Initialize user books management
    initUserBooksManagement();

    function initUserBooksManagement() {
        // Bind click events to book action buttons
        bindBookActionButtons();
        
        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    function bindBookActionButtons() {
        // Handle book action button clicks
        $(document).on('click', '.book-action-btn', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $container = $button.closest('.book-actions');
            var postId = $button.data('post-id');
            var listType = $button.data('list-type');
            var action = $button.data('action');
            var postType = $button.data('post-type') || 'livre';
            
            // Prevent multiple clicks
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Show loading state
            showButtonLoading($button);
            
            // Perform action
            if (action === 'add') {
                addToUserBooks(postId, listType, postType, $button, $container);
            } else if (action === 'remove') {
                removeFromUserBooks(postId, listType, postType, $button, $container);
            }
        });
        
        // Handle quick actions (single click toggles)
        $(document).on('click', '.book-quick-action', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var postId = $button.data('post-id');
            var listType = $button.data('list-type');
            var postType = $button.data('post-type') || 'livre';
            
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Check current state and toggle
            if ($button.hasClass('active')) {
                removeFromUserBooks(postId, listType, postType, $button, $button);
            } else {
                addToUserBooks(postId, listType, postType, $button, $button);
            }
        });
    }

    function addToUserBooks(postId, listType, postType, $button, $container) {
        $.ajax({
            url: userBooksData.ajaxurl,
            type: 'POST',
            data: {
                action: 'add_to_user_books',
                post_id: postId,
                list_type: listType,
                post_type: postType,
                nonce: userBooksData.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update button state
                    updateButtonState($button, 'remove', listType);
                    
                    // Show success message
                    showMessage($container, 'success', getSuccessMessage('add', listType));
                    
                    // Update counter if exists
                    updateCounter($container, listType, 1);
                    
                    // Trigger custom event
                    $(document).trigger('userBookAdded', {
                        postId: postId,
                        listType: listType,
                        postType: postType
                    });
                } else {
                    showMessage($container, 'error', response.data || userBooksData.strings.error);
                }
            },
            error: function() {
                showMessage($container, 'error', userBooksData.strings.error);
            },
            complete: function() {
                hideButtonLoading($button);
            }
        });
    }

    function removeFromUserBooks(postId, listType, postType, $button, $container) {
        $.ajax({
            url: userBooksData.ajaxurl,
            type: 'POST',
            data: {
                action: 'remove_from_user_books',
                post_id: postId,
                list_type: listType,
                post_type: postType,
                nonce: userBooksData.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update button state
                    updateButtonState($button, 'add', listType);
                    
                    // Show success message
                    showMessage($container, 'success', getSuccessMessage('remove', listType));
                    
                    // Update counter if exists
                    updateCounter($container, listType, -1);
                    
                    // Trigger custom event
                    $(document).trigger('userBookRemoved', {
                        postId: postId,
                        listType: listType,
                        postType: postType
                    });
                } else {
                    showMessage($container, 'error', response.data || userBooksData.strings.error);
                }
            },
            error: function() {
                showMessage($container, 'error', userBooksData.strings.error);
            },
            complete: function() {
                hideButtonLoading($button);
            }
        });
    }

    function updateButtonState($button, action, listType) {
        var $container = $button.closest('.book-actions');
        
        // Update button classes and attributes
        $button.removeClass('active').addClass(action === 'remove' ? 'active' : '');
        $button.data('action', action);
        
        // Update button text
        var newText = getButtonText(action, listType);
        $button.find('.btn-text').text(newText);
        
        // Update icon if exists
        var $icon = $button.find('.btn-icon');
        if ($icon.length) {
            var newIcon = getButtonIcon(action, listType);
            $icon.removeClass().addClass('btn-icon ' + newIcon);
        }
        
        // Update tooltip if exists
        if ($button.attr('data-bs-toggle') === 'tooltip') {
            var newTooltip = getButtonTooltip(action, listType);
            $button.attr('data-bs-original-title', newTooltip);
        }
    }

    function getButtonText(action, listType) {
        var strings = userBooksData.strings;
        
        if (action === 'add') {
            switch (listType) {
                case 'wishlist':
                    return strings.addToWishlist;
                case 'read':
                    return strings.markAsRead;
                case 'collection_wishlist':
                    return strings.addToCollectionWishlist;
                case 'missing_albums':
                    return strings.addToMissingAlbums;
                default:
                    return strings.addToWishlist;
            }
        } else {
            switch (listType) {
                case 'wishlist':
                    return strings.removeFromWishlist;
                case 'read':
                    return strings.markAsUnread;
                case 'collection_wishlist':
                    return strings.removeFromCollectionWishlist;
                case 'missing_albums':
                    return strings.removeFromMissingAlbums;
                default:
                    return strings.removeFromWishlist;
            }
        }
    }

    function getButtonIcon(action, listType) {
        if (action === 'add') {
            switch (listType) {
                case 'wishlist':
                    return 'dashicons-heart';
                case 'read':
                    return 'dashicons-yes-alt';
                case 'collection_wishlist':
                    return 'dashicons-star-filled';
                case 'missing_albums':
                    return 'dashicons-minus';
                default:
                    return 'dashicons-plus';
            }
        } else {
            switch (listType) {
                case 'wishlist':
                    return 'dashicons-heart-filled';
                case 'read':
                    return 'dashicons-yes';
                case 'collection_wishlist':
                    return 'dashicons-star-filled';
                case 'missing_albums':
                    return 'dashicons-minus';
                default:
                    return 'dashicons-minus';
            }
        }
    }

    function getButtonTooltip(action, listType) {
        return getButtonText(action, listType);
    }

    function getSuccessMessage(action, listType) {
        var strings = userBooksData.strings;
        
        if (action === 'add') {
            switch (listType) {
                case 'wishlist':
                    return 'Livre ajouté à vos souhaits !';
                case 'read':
                    return 'Livre marqué comme lu !';
                case 'collection_wishlist':
                    return 'Collection ajoutée à vos souhaits !';
                case 'missing_albums':
                    return 'Collection ajoutée aux albums manquants !';
                default:
                    return 'Ajouté avec succès !';
            }
        } else {
            switch (listType) {
                case 'wishlist':
                    return 'Livre retiré de vos souhaits.';
                case 'read':
                    return 'Livre marqué comme non lu.';
                case 'collection_wishlist':
                    return 'Collection retirée de vos souhaits.';
                case 'missing_albums':
                    return 'Collection retirée des albums manquants.';
                default:
                    return 'Retiré avec succès.';
            }
        }
    }

    function showButtonLoading($button) {
        $button.addClass('loading disabled');
        var $text = $button.find('.btn-text');
        if ($text.length) {
            $text.data('original-text', $text.text());
            $text.text(userBooksData.strings.loading);
        }
    }

    function hideButtonLoading($button) {
        $button.removeClass('loading disabled');
        var $text = $button.find('.btn-text');
        if ($text.length && $text.data('original-text')) {
            $text.text($text.data('original-text'));
            $text.removeData('original-text');
        }
    }

    function showMessage($container, type, message) {
        // Remove existing messages
        $container.find('.book-action-message').remove();
        
        // Create message element
        var $message = $('<div class="book-action-message message-' + type + '">' + message + '</div>');
        
        // Add to container
        $container.append($message);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $message.fadeOut(function() {
                $message.remove();
            });
        }, 3000);
    }

    function updateCounter($container, listType, increment) {
        var $counter = $container.find('[data-counter="' + listType + '"]');
        if ($counter.length) {
            var currentCount = parseInt($counter.text()) || 0;
            var newCount = Math.max(0, currentCount + increment);
            $counter.text(newCount);
        }
    }

    // Public API functions
    window.UserBooksManagement = {
        addToUserBooks: addToUserBooks,
        removeFromUserBooks: removeFromUserBooks,
        updateButtonState: updateButtonState
    };
});
