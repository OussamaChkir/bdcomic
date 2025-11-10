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
        
        // Initialize report problem modal
        initReportProblemModal();
        
        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }
    
    function initReportProblemModal() {
        // Create modal HTML if it doesn't exist
        if ($('#report-problem-modal').length === 0) {
            var modalHTML = '<div id="report-problem-modal" class="report-problem-modal">' +
                '<div class="report-problem-modal-content">' +
                '<div class="report-problem-modal-header">' +
                '<h3>' + (userBooksData.strings.reportProblemTitle || 'Signaler un problème') + '</h3>' +
                '<button type="button" class="report-problem-modal-close" aria-label="Fermer">&times;</button>' +
                '</div>' +
                '<div class="report-problem-modal-body">' +
                '<div class="report-problem-modal-message" style="display: none;"></div>' +
                '<label for="report-problem-message">' + (userBooksData.strings.reportProblemMessage || 'Décrivez le problème:') + '</label>' +
                '<textarea id="report-problem-message" placeholder="' + (userBooksData.strings.reportProblemPlaceholder || 'Ex: Informations incorrectes, image manquante...') + '"></textarea>' +
                '</div>' +
                '<div class="report-problem-modal-footer">' +
                '<button type="button" class="report-problem-modal-btn report-problem-modal-btn-cancel">' + (userBooksData.strings.cancel || 'Annuler') + '</button>' +
                '<button type="button" class="report-problem-modal-btn report-problem-modal-btn-submit">' + (userBooksData.strings.submitReport || 'Envoyer') + '</button>' +
                '</div>' +
                '</div>' +
                '</div>';
            $('body').append(modalHTML);
        }
        
        var $modal = $('#report-problem-modal');
        var $message = $modal.find('.report-problem-modal-message');
        var $textarea = $modal.find('#report-problem-message');
        var $submitBtn = $modal.find('.report-problem-modal-btn-submit');
        var currentBookId = null;
        
        // Open modal on report button click
        $(document).on('click', '.report-problem-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            currentBookId = $(this).data('book-id');
            $textarea.val('');
            $message.hide().removeClass('success error');
            $modal.addClass('active');
            $textarea.focus();
        });
        
        // Close modal
        function closeModal() {
            $modal.removeClass('active');
            $textarea.val('');
            $message.hide().removeClass('success error');
            currentBookId = null;
        }
        
        $modal.find('.report-problem-modal-close, .report-problem-modal-btn-cancel').on('click', function() {
            closeModal();
        });
        
        // Close on outside click
        $modal.on('click', function(e) {
            if ($(e.target).is('.report-problem-modal')) {
                closeModal();
            }
        });
        
        // Close on Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $modal.hasClass('active')) {
                closeModal();
            }
        });
        
        // Submit report
        $submitBtn.on('click', function() {
            if (!currentBookId) {
                return;
            }
            
            var message = $textarea.val().trim();
            if (!message) {
                showReportMessage('error', userBooksData.strings.reportProblemMessage || 'Veuillez décrire le problème');
                return;
            }
            
            // Disable submit button
            $submitBtn.prop('disabled', true).text(userBooksData.strings.loading || 'Envoi...');
            
            // Send AJAX request
            $.ajax({
                url: userBooksData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'report_book_problem',
                    post_id: currentBookId,
                    message: message,
                    nonce: userBooksData.reportNonce
                },
                success: function(response) {
                    if (response.success) {
                        showReportMessage('success', response.data.message || userBooksData.strings.reportSuccess || 'Problème signalé avec succès');
                        $textarea.val('');
                        
                        // Close modal after 2 seconds
                        setTimeout(function() {
                            closeModal();
                        }, 2000);
                    } else {
                        showReportMessage('error', response.data.message || userBooksData.strings.reportError || 'Erreur lors de l\'envoi');
                    }
                },
                error: function() {
                    showReportMessage('error', userBooksData.strings.reportError || 'Erreur lors de l\'envoi');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text(userBooksData.strings.submitReport || 'Envoyer');
                }
            });
        });
        
        function showReportMessage(type, text) {
            $message.removeClass('success error').addClass(type).text(text).show();
            setTimeout(function() {
                $message.fadeOut();
            }, 5000);
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
