<?php
// Enqueue styles and scripts
wp_enqueue_style('block-missing-albums-grid', get_template_directory_uri() . '/assets/css/ContentElements/ce-missing-albums-grid.css', array(), '1.0', 'all');
wp_enqueue_script('block-missing-albums-grid', get_template_directory_uri() . '/assets/js/block-missing-albums-grid.js', array('jquery'), '1.0', true);

// Get ACF fields
$block_settings = get_field('missing_albums_grid_settings');
$books_per_page = $block_settings['books_per_page'] ?? 20;
$show_actions = $block_settings['show_actions'] ?? true;
$grid_columns = $block_settings['grid_columns'] ?? 'auto';
$show_search = $block_settings['show_search'] ?? true;

// Get current user ID
$current_user_id = get_current_user_id();
?>

<div class="block-missing-albums-grid">
    <div class="container">
        <?php if ($show_search) : ?>
            <!-- Search Input -->
            <div class="missing-albums-search">
                <div class="search-input-container">
                    <input type="text" id="missing-albums-search-input" placeholder="Rechercher par titre ou collection..." class="form-control">
                    <button type="button" id="clear-missing-albums-search" class="btn btn-secondary clear-search-btn" style="display: none;">
                        <span class="dashicons dashicons-no"></span>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Books Grid -->
        <div class="books-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>" id="missing-albums-grid">
            <?php if (is_user_logged_in()) : ?>
                <?php
                $missing_books = get_user_books($current_user_id, 'missing_albums', 'livre');
                if (!empty($missing_books)) :
                    // Limit to specified number
                    $missing_books = array_slice($missing_books, 0, $books_per_page);
                    foreach ($missing_books as $book_data) :
                        $post = $book_data['post'];
                        // Pass post ID directly as a global variable
                        $GLOBALS['current_book_id'] = $post->ID;
                        get_template_part('template-parts/content-livre-grid');
                    endforeach;
                    wp_reset_postdata();
                else :
                    echo '<div class="no-books-message"><p>Vous n\'avez encore ajouté aucun livre à vos albums manquants.</p></div>';
                endif;
                ?>
            <?php else : ?>
                <div class="login-required-message">
                    <p>Vous devez être connecté pour voir vos albums manquants.</p>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-primary">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Localize script with data
wp_localize_script('block-missing-albums-grid', 'missingAlbumsGridData', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('missing_albums_grid_nonce'),
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
