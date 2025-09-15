<?php
/**
 * Template Name: User Dashboard
 * 
 * @package bdcomic_theme
 */

// Redirect to login if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="user-books-dashboard">
            <header class="dashboard-header">
                <h1 class="dashboard-title">
                    <?php _e('Mon Tableau de Bord', 'bdcomic_theme'); ?>
                </h1>
                <p class="dashboard-subtitle">
                    <?php _e('Gérez vos livres, souhaits et collections', 'bdcomic_theme'); ?>
                </p>
            </header>

            <?php
            $current_user = wp_get_current_user();
            $user_stats = get_user_books_stats($current_user->ID);
            $list_labels = get_list_type_labels();
            $list_descriptions = get_list_type_descriptions();
            ?>

            <!-- User Statistics -->
            <div class="user-books-stats">
                <div class="user-books-stat-card">
                    <h3><?php echo $list_labels['wishlist']; ?></h3>
                    <div class="user-books-stat-number"><?php echo $user_stats['wishlist_books']; ?></div>
                    <div class="user-books-stat-description">
                        <?php echo $list_descriptions['wishlist']; ?>
                    </div>
                </div>

                <div class="user-books-stat-card">
                    <h3><?php echo $list_labels['read']; ?></h3>
                    <div class="user-books-stat-number"><?php echo $user_stats['read_books']; ?></div>
                    <div class="user-books-stat-description">
                        <?php echo $list_descriptions['read']; ?>
                    </div>
                </div>

                <div class="user-books-stat-card">
                    <h3><?php echo $list_labels['collection_wishlist']; ?></h3>
                    <div class="user-books-stat-number"><?php echo $user_stats['collection_wishlist']; ?></div>
                    <div class="user-books-stat-description">
                        <?php echo $list_descriptions['collection_wishlist']; ?>
                    </div>
                </div>

                <div class="user-books-stat-card">
                    <h3><?php echo $list_labels['missing_albums']; ?></h3>
                    <div class="user-books-stat-number"><?php echo $user_stats['missing_albums']; ?></div>
                    <div class="user-books-stat-description">
                        <?php echo $list_descriptions['missing_albums']; ?>
                    </div>
                </div>
            </div>

            <!-- User Books Sections -->
            <div class="user-books-sections">
                <!-- Wishlist Section -->
                <div class="user-books-section">
                    <h2><?php echo $list_labels['wishlist']; ?></h2>
                    <div class="user-books-list-container">
                        <?php
                        $wishlist_books = get_user_books($current_user->ID, 'wishlist', 'livre');
                        if (!empty($wishlist_books)) :
                        ?>
                            <ul class="user-books-list">
                                <?php foreach ($wishlist_books as $book_data) : 
                                    $book = $book_data['post'];
                                    $photo_devant = get_field('photo_devant', $book->ID);
                                    $titre = get_field('titre_livre', $book->ID);
                                ?>
                                    <li class="user-books-list-item">
                                        <div class="user-books-list-item-info">
                                            <?php if ($photo_devant) : ?>
                                                <img src="<?php echo esc_url($photo_devant['sizes']['thumbnail']); ?>" 
                                                     alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                                     class="book-thumbnail">
                                            <?php endif; ?>
                                            <div class="book-details">
                                                <h4 class="user-books-list-item-title">
                                                    <a href="<?php echo get_permalink($book->ID); ?>">
                                                        <?php echo $titre ? esc_html($titre) : esc_html($book->post_title); ?>
                                                    </a>
                                                </h4>
                                                <div class="user-books-list-item-meta">
                                                    Ajouté le <?php echo date_i18n(get_option('date_format'), strtotime($book_data['added_date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-books-list-item-actions">
                                            <button class="book-action-btn active" 
                                                    data-post-id="<?php echo $book->ID; ?>" 
                                                    data-list-type="wishlist" 
                                                    data-action="remove"
                                                    data-post-type="livre">
                                                <span class="btn-icon dashicons dashicons-heart-filled"></span>
                                                <span class="btn-text"><?php _e('Retirer', 'bdcomic_theme'); ?></span>
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="user-books-empty">
                                <div class="user-books-empty-icon">
                                    <span class="dashicons dashicons-heart"></span>
                                </div>
                                <h3><?php _e('Aucun livre dans vos souhaits', 'bdcomic_theme'); ?></h3>
                                <p><?php _e('Commencez à explorer nos livres et ajoutez ceux qui vous intéressent à vos souhaits.', 'bdcomic_theme'); ?></p>
                                <a href="<?php echo get_post_type_archive_link('livre'); ?>" class="btn btn-primary">
                                    <?php _e('Explorer les livres', 'bdcomic_theme'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Read Books Section -->
                <div class="user-books-section">
                    <h2><?php echo $list_labels['read']; ?></h2>
                    <div class="user-books-list-container">
                        <?php
                        $read_books = get_user_books($current_user->ID, 'read', 'livre');
                        if (!empty($read_books)) :
                        ?>
                            <ul class="user-books-list">
                                <?php foreach ($read_books as $book_data) : 
                                    $book = $book_data['post'];
                                    $photo_devant = get_field('photo_devant', $book->ID);
                                    $titre = get_field('titre_livre', $book->ID);
                                ?>
                                    <li class="user-books-list-item">
                                        <div class="user-books-list-item-info">
                                            <?php if ($photo_devant) : ?>
                                                <img src="<?php echo esc_url($photo_devant['sizes']['thumbnail']); ?>" 
                                                     alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                                     class="book-thumbnail">
                                            <?php endif; ?>
                                            <div class="book-details">
                                                <h4 class="user-books-list-item-title">
                                                    <a href="<?php echo get_permalink($book->ID); ?>">
                                                        <?php echo $titre ? esc_html($titre) : esc_html($book->post_title); ?>
                                                    </a>
                                                </h4>
                                                <div class="user-books-list-item-meta">
                                                    Marqué comme lu le <?php echo date_i18n(get_option('date_format'), strtotime($book_data['added_date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-books-list-item-actions">
                                            <button class="book-action-btn active" 
                                                    data-post-id="<?php echo $book->ID; ?>" 
                                                    data-list-type="read" 
                                                    data-action="remove"
                                                    data-post-type="livre">
                                                <span class="btn-icon dashicons dashicons-yes"></span>
                                                <span class="btn-text"><?php _e('Marquer non lu', 'bdcomic_theme'); ?></span>
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="user-books-empty">
                                <div class="user-books-empty-icon">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                </div>
                                <h3><?php _e('Aucun livre marqué comme lu', 'bdcomic_theme'); ?></h3>
                                <p><?php _e('Marquez les livres que vous avez lus pour suivre vos lectures.', 'bdcomic_theme'); ?></p>
                                <a href="<?php echo get_post_type_archive_link('livre'); ?>" class="btn btn-primary">
                                    <?php _e('Explorer les livres', 'bdcomic_theme'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Collection Wishlist Section -->
                <div class="user-books-section">
                    <h2><?php echo $list_labels['collection_wishlist']; ?></h2>
                    <div class="user-books-list-container">
                        <?php
                        $collection_wishlist = get_user_books($current_user->ID, 'collection_wishlist', 'collection');
                        if (!empty($collection_wishlist)) :
                        ?>
                            <ul class="user-books-list">
                                <?php foreach ($collection_wishlist as $collection_data) : 
                                    $collection = $collection_data['post'];
                                    $logo = get_field('logo_collection', $collection->ID);
                                    $nom = get_field('nom_collection', $collection->ID);
                                ?>
                                    <li class="user-books-list-item">
                                        <div class="user-books-list-item-info">
                                            <?php if ($logo) : ?>
                                                <img src="<?php echo esc_url($logo['sizes']['thumbnail']); ?>" 
                                                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                                                     class="book-thumbnail">
                                            <?php endif; ?>
                                            <div class="book-details">
                                                <h4 class="user-books-list-item-title">
                                                    <a href="<?php echo get_permalink($collection->ID); ?>">
                                                        <?php echo $nom ? esc_html($nom) : esc_html($collection->post_title); ?>
                                                    </a>
                                                </h4>
                                                <div class="user-books-list-item-meta">
                                                    Ajouté le <?php echo date_i18n(get_option('date_format'), strtotime($collection_data['added_date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-books-list-item-actions">
                                            <button class="book-action-btn active" 
                                                    data-post-id="<?php echo $collection->ID; ?>" 
                                                    data-list-type="collection_wishlist" 
                                                    data-action="remove"
                                                    data-post-type="collection">
                                                <span class="btn-icon dashicons dashicons-star-filled"></span>
                                                <span class="btn-text"><?php _e('Retirer', 'bdcomic_theme'); ?></span>
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="user-books-empty">
                                <div class="user-books-empty-icon">
                                    <span class="dashicons dashicons-star-filled"></span>
                                </div>
                                <h3><?php _e('Aucune collection dans vos souhaits', 'bdcomic_theme'); ?></h3>
                                <p><?php _e('Ajoutez des collections à vos souhaits pour suivre leurs nouveaux albums.', 'bdcomic_theme'); ?></p>
                                <a href="<?php echo get_post_type_archive_link('collection'); ?>" class="btn btn-primary">
                                    <?php _e('Explorer les collections', 'bdcomic_theme'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Missing Albums Section -->
                <div class="user-books-section">
                    <h2><?php echo $list_labels['missing_albums']; ?></h2>
                    <div class="user-books-list-container">
                        <?php
                        $missing_albums = get_user_books($current_user->ID, 'missing_albums', 'collection');
                        if (!empty($missing_albums)) :
                        ?>
                            <ul class="user-books-list">
                                <?php foreach ($missing_albums as $collection_data) : 
                                    $collection = $collection_data['post'];
                                    $logo = get_field('logo_collection', $collection->ID);
                                    $nom = get_field('nom_collection', $collection->ID);
                                ?>
                                    <li class="user-books-list-item">
                                        <div class="user-books-list-item-info">
                                            <?php if ($logo) : ?>
                                                <img src="<?php echo esc_url($logo['sizes']['thumbnail']); ?>" 
                                                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                                                     class="book-thumbnail">
                                            <?php endif; ?>
                                            <div class="book-details">
                                                <h4 class="user-books-list-item-title">
                                                    <a href="<?php echo get_permalink($collection->ID); ?>">
                                                        <?php echo $nom ? esc_html($nom) : esc_html($collection->post_title); ?>
                                                    </a>
                                                </h4>
                                                <div class="user-books-list-item-meta">
                                                    Ajouté le <?php echo date_i18n(get_option('date_format'), strtotime($collection_data['added_date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-books-list-item-actions">
                                            <button class="book-action-btn active" 
                                                    data-post-id="<?php echo $collection->ID; ?>" 
                                                    data-list-type="missing_albums" 
                                                    data-action="remove"
                                                    data-post-type="collection">
                                                <span class="btn-icon dashicons dashicons-minus"></span>
                                                <span class="btn-text"><?php _e('Retirer', 'bdcomic_theme'); ?></span>
                                            </button>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="user-books-empty">
                                <div class="user-books-empty-icon">
                                    <span class="dashicons dashicons-minus"></span>
                                </div>
                                <h3><?php _e('Aucun album manquant', 'bdcomic_theme'); ?></h3>
                                <p><?php _e('Ajoutez des collections à vos albums manquants pour suivre les albums que vous n\'avez pas encore.', 'bdcomic_theme'); ?></p>
                                <a href="<?php echo get_post_type_archive_link('collection'); ?>" class="btn btn-primary">
                                    <?php _e('Explorer les collections', 'bdcomic_theme'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>

