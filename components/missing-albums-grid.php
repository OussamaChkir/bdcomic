<?php
/**
 * Missing Albums Grid Component
 * Displays books from user's collections that haven't been read yet
 * 
 * @package bdcomic_theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get ACF fields
$title = get_field('title') ?: __('Albums Manquants', 'bdcomic_theme');
$description = get_field('description');
$books_per_row = get_field('books_per_row') ?: '4';
$show_status_icons = get_field('show_status_icons') !== false ? get_field('show_status_icons') : true;

// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="login-required-message">';
    echo '<p>' . __('Vous devez être connecté pour voir vos albums manquants.', 'bdcomic_theme') . '</p>';
    echo '<a href="' . wp_login_url(get_permalink()) . '" class="btn btn-primary">' . __('Se connecter', 'bdcomic_theme') . '</a>';
    echo '</div>';
    return;
}

$current_user_id = get_current_user_id();

// Get user's read books
$read_books_data = get_user_books($current_user_id, 'read', 'livre');
$read_book_ids = array();
if (!empty($read_books_data)) {
    foreach ($read_books_data as $data) {
        $read_book_ids[] = $data['post']->ID;
    }
}

// Get user's owned books (to check ownership status)
$owned_books_data = get_user_books($current_user_id, 'owned', 'livre');
$owned_book_ids = array();
if (!empty($owned_books_data)) {
    foreach ($owned_books_data as $data) {
        $owned_book_ids[] = $data['post']->ID;
    }
}

// Get collections from read books
$collection_ids = array();
if (!empty($read_books_data)) {
    foreach ($read_books_data as $data) {
        $book_id = $data['post']->ID;
        $collection = get_field('collection', $book_id);
        if ($collection) {
            $collection_ids[] = $collection->ID;
        }
    }
}
$collection_ids = array_unique($collection_ids);

$missing_books = array();

if (!empty($collection_ids)) {
    // Query all books in these collections
    $args = array(
        'post_type' => 'livre',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'collection',
                'value' => $collection_ids,
                'compare' => 'IN'
            )
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => 'n_sortie', // Sort by volume number
        'order' => 'ASC'
    );

    $all_collection_books = get_posts($args);

    foreach ($all_collection_books as $book) {
        // If book is NOT read, add to missing
        if (!in_array($book->ID, $read_book_ids)) {
            $missing_books[] = $book;
        }
    }
}

// Group by collection for display
$grouped_books = array();
foreach ($missing_books as $book) {
    $collection = get_field('collection', $book->ID);
    if ($collection) {
        $collection_id = $collection->ID;
        if (!isset($grouped_books[$collection_id])) {
            $grouped_books[$collection_id] = array(
                'name' => $collection->post_title,
                'books' => array()
            );
        }
        $grouped_books[$collection_id]['books'][] = $book;
    }
}

// Sort collections by name
uasort($grouped_books, function ($a, $b) {
    return strcmp($a['name'], $b['name']);
});
?>

<div class="missing-albums-grid" data-user-id="<?php echo $current_user_id; ?>">
    <div class="missing-albums-header">
        <h2 class="missing-albums-title"><?php echo esc_html($title); ?></h2>
        <?php if ($description): ?>
            <div class="missing-albums-description">
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($grouped_books)): ?>
        <div class="no-books-message">
            <p><?php _e('Vous êtes à jour ! Aucun album manquant dans vos collections lues.', 'bdcomic_theme'); ?></p>
        </div>
    <?php else: ?>
        <div class="missing-collections-list">
            <?php foreach ($grouped_books as $collection_id => $data): ?>
                <div class="collection-group">
                    <h3 class="collection-title">
                        <a href="<?php echo get_permalink($collection_id); ?>">
                            <?php echo esc_html($data['name']); ?>
                        </a>
                    </h3>

                    <div class="books-grid <?php echo 'grid-cols-' . $books_per_row; ?>">
                        <?php foreach ($data['books'] as $book): ?>
                            <?php
                            $is_owned = in_array($book->ID, $owned_book_ids);
                            $photo_devant = get_field('photo_devant', $book->ID);
                            $titre = get_field('titre_livre', $book->ID) ?: $book->post_title;
                            $n_sortie = get_field('n_sortie', $book->ID);
                            $n_frise = get_field('n_frise', $book->ID);

                            // Set global post for template part if needed, or just render manually
                            // Rendering manually for better control over "missing" specific UI
                            ?>
                            <div class="book-item missing-book" data-book-id="<?php echo $book->ID; ?>">
                                <div class="book-cover">
                                    <?php if ($photo_devant): ?>
                                        <img src="<?php echo esc_url($photo_devant['sizes']['medium']); ?>"
                                            alt="<?php echo esc_attr($photo_devant['alt']); ?>" class="book-image">
                                    <?php else: ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-book"></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($show_status_icons && $is_owned): ?>
                                        <div class="book-status-icons">
                                            <span class="status-icon owned" title="<?php _e('Possédé', 'bdcomic_theme'); ?>">
                                                <span class="dashicons dashicons-yes"></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="book-actions">
                                        <!-- Quick Mark as Read -->
                                        <button class="book-quick-action" data-post-id="<?php echo $book->ID; ?>"
                                            data-list-type="read" data-action="add"
                                            title="<?php _e('Marquer comme lu', 'bdcomic_theme'); ?>">
                                            <span class="dashicons dashicons-yes"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="book-info">
                                    <h4 class="book-title">
                                        <a href="<?php echo get_permalink($book->ID); ?>">
                                            <?php echo esc_html($titre); ?>
                                        </a>
                                    </h4>
                                    <div class="book-meta">
                                        <?php if ($n_sortie): ?>
                                            <span class="meta-item">
                                                <?php _e('N° Sortie', 'bdcomic_theme'); ?>                 <?php echo esc_html($n_sortie); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($n_frise): ?>
                                            <span class="meta-item">
                                                <?php _e('N° Frise', 'bdcomic_theme'); ?>                 <?php echo esc_html($n_frise); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    jQuery(document).ready(function ($) {
        // Listen for book marked as read
        $(document).on('userBookAdded', function (e, data) {
            if (data.listType === 'read') {
                // Find the book in the grid and remove it
                var $book = $('.missing-albums-grid .book-item[data-book-id="' + data.postId + '"]');
                if ($book.length) {
                    $book.fadeOut(function () {
                        var $collection = $book.closest('.collection-group');
                        $book.remove();

                        // If collection is empty, remove it too
                        if ($collection.find('.book-item').length === 0) {
                            $collection.fadeOut(function () {
                                $(this).remove();
                                // If all collections empty, show message
                                if ($('.missing-albums-grid .collection-group').length === 0) {
                                    $('.missing-collections-list').html('<div class="no-books-message"><p><?php _e('Vous êtes à jour ! Aucun album manquant dans vos collections lues.', 'bdcomic_theme'); ?></p></div>');
                                }
                            });
                        }
                    });
                }
            }
        });
    });
</script>