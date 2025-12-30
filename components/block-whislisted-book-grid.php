<?php
// Enqueue styles and scripts
wp_enqueue_style('block-whislisted-book-grid', get_template_directory_uri() . '/assets/css/ContentElements/ce-whislisted-book-grid.css', array(), '1.0', 'all');
wp_enqueue_script('block-whislisted-book-grid', get_template_directory_uri() . '/assets/js/block-whislisted-book-grid.js', array('jquery'), '1.0', true);

// Get ACF fields
$block_settings = get_field('whislisted_book_grid_settings');
$books_per_page = $block_settings['books_per_page'] ?? 20;
$show_actions = $block_settings['show_actions'] ?? true;
$grid_columns = $block_settings['grid_columns'] ?? 'auto';
$show_search = $block_settings['show_search'] ?? true;

// Get current user ID
$current_user_id = get_current_user_id();
?>

<div class="block-whislisted-book-grid">
    <div class="container">
        <?php if ($show_search): ?>
            <!-- Search Input -->
            <div class="whislisted-book-search">
                <div class="search-input-container">
                    <input type="text" id="whislisted-book-search-input" placeholder="Rechercher par titre ou collection..."
                        class="form-control">
                    <button type="button" id="clear-search" class="btn btn-secondary clear-search-btn"
                        style="display: none;">
                        <span class="dashicons dashicons-no"></span>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Books Grid -->
        <div class="whislisted-books-container" id="whislisted-books-grid">
            <?php if (is_user_logged_in()): ?>
                <?php
                $wishlist_books = get_user_books($current_user_id, 'wishlist', 'livre');

                if (!empty($wishlist_books)):
                    // Group books
                    $grouped_books = array();
                    $autres_books = array();

                    foreach ($wishlist_books as $book_data) {
                        $book = $book_data['post'];
                        $collection = get_field('collection', $book->ID);
                        $sous_collection = get_field('sous_collection', $book->ID);

                        $group_id = 0;
                        $group_name = '';

                        if ($sous_collection) {
                            $group_id = $sous_collection->ID;
                            $group_name = $sous_collection->post_title;
                        } elseif ($collection) {
                            $group_id = $collection->ID;
                            $group_name = $collection->post_title;
                        }

                        if ($group_id) {
                            if (!isset($grouped_books[$group_id])) {
                                $grouped_books[$group_id] = array(
                                    'name' => $group_name,
                                    'books' => array()
                                );
                            }
                            $grouped_books[$group_id]['books'][] = $book_data;
                        } else {
                            $autres_books[] = $book_data;
                        }
                    }

                    // Sort collections by name
                    uasort($grouped_books, function ($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });

                    // Add "Autres" at the end if exists
                    if (!empty($autres_books)) {
                        $grouped_books['autres'] = array(
                            'name' => __('Autres', 'bdcomic_theme'),
                            'books' => $autres_books
                        );
                    }

                    // Display Groups
                    echo '<div class="whislisted-collections-list">';
                    foreach ($grouped_books as $group_id => $data):
                        ?>
                        <div class="collection-group" data-collection-id="<?php echo esc_attr($group_id); ?>">
                            <div class="collection-header collapsed">
                                <h3 class="collection-title">
                                    <?php echo esc_html($data['name']); ?>
                                    <span class="book-count">(<?php echo count($data['books']); ?>)</span>
                                </h3>
                                <span class="collection-toggle-icon">
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                </span>
                            </div>

                            <div class="collection-books" style="display: none;">
                                <div class="books-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>">
                                    <?php foreach ($data['books'] as $book_data):
                                        $post = $book_data['post'];
                                        $GLOBALS['current_book_id'] = $post->ID;
                                        get_template_part('template-parts/content-livre-grid');
                                    endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    echo '</div>'; // .whislisted-collections-list
            
                    wp_reset_postdata();
                else:
                    echo '<div class="no-books-message"><p>Votre liste de souhaits est vide.</p></div>';
                endif;
                ?>
            <?php else: ?>
                <div class="login-required-message">
                    <p>Vous devez être connecté pour voir votre liste de souhaits.</p>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-primary">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Localize script with data
wp_localize_script('block-whislisted-book-grid', 'whislistedBookGridData', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('whislisted_book_grid_nonce'),
    'currentUserId' => $current_user_id,
    'booksPerPage' => $books_per_page,
    'showActions' => $show_actions,
    'showSearch' => $show_search,
    'strings' => array(
        'loading' => __('Chargement...', 'bdcomic_theme'),
        'error' => __('Une erreur est survenue', 'bdcomic_theme'),
        'noBooks' => __('Aucun livre trouvé', 'bdcomic_theme'),
        'loginRequired' => __('Vous devez être connecté', 'bdcomic_theme')
    )
));
?>