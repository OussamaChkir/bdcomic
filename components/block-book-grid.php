<?php
// Enqueue styles and scripts
wp_enqueue_style('block-book-grid', get_template_directory_uri() . '/assets/css/ContentElements/ce-book-grid.css', array(), '1.0', 'all');
wp_enqueue_script('block-book-grid', get_template_directory_uri() . '/assets/js/block-book-grid.js', array('jquery'), '1.0', true);

// Get ACF fields
$block_settings = get_field('book_grid_settings');
$show_tabs = $block_settings['show_tabs'] ?? true;
$books_per_tab = $block_settings['books_per_tab'] ?? 20;
$show_actions = $block_settings['show_actions'] ?? true;
$grid_columns = $block_settings['grid_columns'] ?? 'auto';

// Get current user ID
$current_user_id = get_current_user_id();
?>

<div class="block-book-grid">
    <div class="container">
        <?php if ($show_tabs) : ?>
            <!-- Tab Navigation -->
            <div class="book-grid-tabs">
                <ul class="nav nav-tabs" id="bookGridTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="latest-tab" data-bs-toggle="tab" data-bs-target="#latest" type="button" role="tab" aria-controls="latest" aria-selected="true">
                            <span class="tab-icon dashicons dashicons-clock"></span>
                            <span class="tab-text">Dernières acquisitions</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab" aria-controls="read" aria-selected="false">
                            <span class="tab-icon dashicons dashicons-yes"></span>
                            <span class="tab-text">Dernières lectures</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab" aria-controls="wishlist" aria-selected="false">
                            <span class="tab-icon dashicons dashicons-heart"></span>
                            <span class="tab-text">Mes souhaits</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="bookGridTabContent">
                <!-- Latest Books Tab -->
                <div class="tab-pane fade show active" id="latest" role="tabpanel" aria-labelledby="latest-tab">
                    <div class="tab-header">
                        <h3>Dernières acquisitions</h3>
                        <p class="tab-description">Les 20 derniers livres ajoutés à la base de données</p>
                    </div>
                    <div class="books-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>" id="latest-books-grid">
                        <?php
                        // Query for latest 20 books
                        $latest_books = new WP_Query(array(
                            'post_type' => 'livre',
                            'posts_per_page' => $books_per_tab,
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'meta_query' => array(
                                array(
                                    'key' => 'photo_devant',
                                    'compare' => 'EXISTS'
                                )
                            )
                        ));

                        if ($latest_books->have_posts()) :
                            while ($latest_books->have_posts()) : $latest_books->the_post();
                                get_template_part('template-parts/content-livre-grid');
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<div class="no-books-message"><p>Aucun livre trouvé.</p></div>';
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Read Books Tab -->
                <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
                    <div class="tab-header">
                        <h3>Dernières lectures</h3>
                        <p class="tab-description">Livres que vous avez marqués comme lus</p>
                    </div>
                    <div class="books-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>" id="read-books-grid">
                        <?php if (is_user_logged_in()) : ?>
                            <?php
                            $read_books = get_user_books($current_user_id, 'read', 'livre');
                            if (!empty($read_books)) :
                                // Limit to specified number
                                $read_books = array_slice($read_books, 0, $books_per_tab);
                                foreach ($read_books as $book_data) :
                                    $post = $book_data['post'];
                                    // Pass post ID directly as a global variable
                                    $GLOBALS['current_book_id'] = $post->ID;
                                    get_template_part('template-parts/content-livre-grid');
                                endforeach;
                                wp_reset_postdata();
                            else :
                                echo '<div class="no-books-message"><p>Vous n\'avez encore marqué aucun livre comme lu.</p></div>';
                            endif;
                            ?>
                        <?php else : ?>
                            <div class="login-required-message">
                                <p>Vous devez être connecté pour voir vos livres lus.</p>
                                <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-primary">Se connecter</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Wishlist Tab -->
                <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                    <div class="tab-header">
                        <h3>Mes souhaits</h3>
                        <p class="tab-description">Livres que vous souhaitez lire</p>
                    </div>
                    <div class="books-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>" id="wishlist-books-grid">
                        <?php if (is_user_logged_in()) : ?>
                            <?php
                            $wishlist_books = get_user_books($current_user_id, 'wishlist', 'livre');
                            if (!empty($wishlist_books)) :
                                // Limit to specified number
                                $wishlist_books = array_slice($wishlist_books, 0, $books_per_tab);
                                foreach ($wishlist_books as $book_data) :
                                    $post = $book_data['post'];
                                    // Pass post ID directly as a global variable
                                    $GLOBALS['current_book_id'] = $post->ID;
                                    get_template_part('template-parts/content-livre-grid');
                                endforeach;
                                wp_reset_postdata();
                            else :
                                echo '<div class="no-books-message"><p>Votre liste de souhaits est vide.</p></div>';
                            endif;
                            ?>
                        <?php else : ?>
                            <div class="login-required-message">
                                <p>Vous devez être connecté pour voir votre liste de souhaits.</p>
                                <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-primary">Se connecter</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- No tabs - show all books in one grid -->
            <div class="books-grid single-grid <?php echo $grid_columns !== 'auto' ? 'grid-cols-' . $grid_columns : ''; ?>">
                <?php
                // Query for latest books
                $all_books = new WP_Query(array(
                    'post_type' => 'livre',
                    'posts_per_page' => $books_per_tab,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
                        array(
                            'key' => 'photo_devant',
                            'compare' => 'EXISTS'
                        )
                    )
                ));

                if ($all_books->have_posts()) :
                    while ($all_books->have_posts()) : $all_books->the_post();
                        get_template_part('template-parts/content-livre-grid');
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="no-books-message"><p>Aucun livre trouvé.</p></div>';
                endif;
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Localize script with data
wp_localize_script('block-book-grid', 'bookGridData', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('book_grid_nonce'),
    'currentUserId' => $current_user_id,
    'booksPerTab' => $books_per_tab,
    'showActions' => $show_actions,
    'strings' => array(
        'loading' => __('Chargement...', 'bdcomic_theme'),
        'error' => __('Une erreur est survenue', 'bdcomic_theme'),
        'noBooks' => __('Aucun livre trouvé', 'bdcomic_theme'),
        'loginRequired' => __('Vous devez être connecté', 'bdcomic_theme')
    )
));
?>
