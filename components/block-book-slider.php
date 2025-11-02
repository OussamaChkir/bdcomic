<?php

// Enqueue Swiper styles from CDN for better performance
if (!wp_style_is('swiper', 'enqueued') && !wp_style_is('swiper', 'done')) {
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0', 'all');
}
wp_enqueue_style('block-book-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-book-slider.css', array('swiper'), '1.0', 'all');

// Check if we have books before enqueuing scripts
$temp_query = new WP_Query(array(
    'post_type' => 'livre',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
));

$has_books = $temp_query->have_posts();
wp_reset_postdata();

// Add preload hints for CDN resources if we have books
if ($has_books) {
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" as="script">';
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style">';
    }, 1);
}

// Only enqueue scripts if we have books
if ($has_books) {
    // Enqueue Swiper script from CDN for better performance
    if (!wp_script_is('swiper', 'enqueued') && !wp_script_is('swiper', 'done')) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    }
    wp_enqueue_script('block-book-slider', get_template_directory_uri() . '/assets/js/block-book-slider.js', array('swiper'), '1.0', true);
}

// Get ACF fields for slider settings
$block_settings = get_field('book_slider_settings');
$books_per_slider = $block_settings['books_per_slider'] ?? 10;
$slides_to_show = $block_settings['slides_to_show'] ?? 4;
$slides_to_scroll = $block_settings['slides_to_scroll'] ?? 1;
$autoplay = $block_settings['autoplay'] ?? true;
$autoplay_speed = $block_settings['autoplay_speed'] ?? 3000;
$infinite = $block_settings['infinite'] ?? true;
$arrows = $block_settings['arrows'] ?? true;
$dots = $block_settings['dots'] ?? true;
$speed = $block_settings['speed'] ?? 500;
?>

<?php if ($has_books) : ?>
<div class="block-book-slider">
    <div class="container">
        <div class="slider-header">
            <div class="slider-title-section">
                <h2 class="slider-title">Derniers livres ajoutés</h2>
                <p class="slider-description">Découvrez les nouveaux livres de la base de données</p>
            </div>
            <div class="slider-actions">
                <a href="<?php echo get_post_type_archive_link('livre'); ?>" class="view-all-books-btn">
                    Voir tous les livres
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                </a>
            </div>
        </div>

        <div class="swiper book-slider" id="book-slider">
            <div class="swiper-wrapper">
            <?php
            // Query for latest books with optimized performance
            $books_query = new WP_Query(array(
                'post_type' => 'livre',
                'posts_per_page' => min($books_per_slider, 20), // Limit to max 20 posts
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'no_found_rows' => true, // Skip pagination count
                'update_post_meta_cache' => false, // Skip meta cache
                'update_post_term_cache' => false // Skip term cache
            ));

            if ($books_query->have_posts() && $books_query->post_count > 0) :
                // Debug: Log number of books found
                if (WP_DEBUG) {
                    error_log('Book Slider: Found ' . $books_query->post_count . ' books');
                }
                
                while ($books_query->have_posts()) : $books_query->the_post();
                    // Get ACF fields
                    $photo_devant = get_field('photo_devant');
                    
                    // Debug: Log image data structure
                    if (WP_DEBUG && $photo_devant) {
                        error_log('Book Slider Debug - Photo Devant: ' . print_r($photo_devant, true));
                    }
                    
                    // Fallback: Try to get image using post ID if ACF fails
                    if (!$photo_devant) {
                        $photo_devant = get_field('photo_devant', get_the_ID());
                        if (WP_DEBUG) {
                            error_log('Book Slider Debug - Fallback attempt for post ID: ' . get_the_ID());
                        }
                    }
                    $photo_derriere = get_field('photo_derriere');
                    $titre = get_field('titre_livre');
                    $variante = get_field('variante');
                    $maison_edition = get_field('maison_d\'edition');
                    $collection = get_field('collection');
                    $tirage_limite = get_field('tirage_limite');
                    $n_sortie = get_field('n_sortie');
                    $date_sortie = get_field('date_sortie_livre');
                    $nombre_pages = get_field('nombre_de_pages');
                    $resume = get_field('resume_livre');
                    $equipe_creative = get_field('equipe_creative');
                    ?>

                    <div class="swiper-slide book-slide">
                        <article class="">
                        <a href="<?php the_permalink(); ?>">
                        <div class="livre-content">

                            <div class="livre-covers">
                                <div class="cover-front">
                                    <?php if ($photo_devant) : ?>
                                        <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                             alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                             class="livre-cover">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" alt="No Image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="livre-details">
                                <h2 class="livre-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                                    </a>
                                </h2>
                            </div>
                            <div class="livre-actions">
                                    <?php if (is_user_logged_in()) : ?>
                                    <div class="book-quick-actions">
                                        <?php
                                        $current_user_id = get_current_user_id();
                                        $post_id = get_the_ID();
                                        
                                        // Quick wishlist button
                                        $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                                        ?>
                                        <button class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>" 
                                                data-post-id="<?php echo $post_id; ?>" 
                                                data-list-type="wishlist"
                                                data-post-type="livre"
                                                data-bs-toggle="tooltip" 
                                                title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                            <span class="dashicons <?php echo $in_wishlist ? 'dashicons-heart-filled' : 'dashicons-heart'; ?>"></span>
                                        </button>
                                        
                                        <?php
                                        // Quick read button
                                        $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                                        ?>
                                        <button class="book-quick-action <?php echo $is_read ? 'active' : ''; ?>" 
                                                data-post-id="<?php echo $post_id; ?>" 
                                                data-list-type="read"
                                                data-post-type="livre"
                                                data-bs-toggle="tooltip" 
                                                title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                                            <span class="dashicons <?php echo $is_read ? 'dashicons-yes' : 'dashicons-yes-alt'; ?>"></span>
                                        </button>

                                        <?php
                                        // Quick missing albums button for book
                                        $in_missing_albums = is_book_in_user_list($current_user_id, $post_id, 'missing_albums');
                                        ?>
                                        <button class="book-quick-action <?php echo $in_missing_albums ? 'active' : ''; ?>" 
                                                data-post-id="<?php echo $post_id; ?>" 
                                                data-list-type="missing_albums"
                                                data-post-type="livre"
                                                data-bs-toggle="tooltip" 
                                                title="<?php echo $in_missing_albums ? __('Retirer des albums manquants', 'bdcomic_theme') : __('Ajouter aux albums manquants', 'bdcomic_theme'); ?>">
                                            <span class="dashicons dashicons-minus"></span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                                </a>
                        </article>
                    </div>

                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-books-message">
                    <p>Aucun livre trouvé.</p>
                </div>
            <?php endif; ?>
            </div>
            
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

<?php
// Localize script with slider settings
wp_localize_script('block-book-slider', 'bookSliderData', array(
    'slidesToShow' => $slides_to_show,
    'slidesToScroll' => $slides_to_scroll,
    'autoplay' => $autoplay,
    'autoplaySpeed' => $autoplay_speed,
    'infinite' => $infinite,
    'arrows' => $arrows,
    'dots' => $dots,
    'speed' => $speed
));
?>
<?php endif; ?>
