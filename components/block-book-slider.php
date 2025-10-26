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
                        <article class="book-card">
                            <div class="book-image-wrapper">
                                <!-- Debug info (remove in production) -->
                                <?php if (WP_DEBUG) : ?>
                                    <div style="position: absolute; top: 0; left: 0; background: rgba(0,0,0,0.8); color: white; padding: 5px; font-size: 10px; z-index: 1000;">
                                        <?php if ($photo_devant) : ?>
                                            Has Image: <?php echo isset($photo_devant['url']) ? 'Yes' : 'No'; ?><br>
                                            URL: <?php echo isset($photo_devant['url']) ? $photo_devant['url'] : 'None'; ?>
                                        <?php else : ?>
                                            No Image Data
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="book-link">
                                    <?php if ($photo_devant) : ?>
                                        <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                             alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                             class="book-cover">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" 
                                                 alt="No Image" 
                                                 class="book-cover placeholder">
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <?php if ($tirage_limite) : ?>
                                    <div class="book-badge limited-badge">
                                        <span class="dashicons dashicons-star-filled"></span>
                                        Tirage limité
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($variante) : ?>
                                    <div class="book-badge variant-badge">
                                        <span class="dashicons dashicons-format-image"></span>
                                        Variante
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="book-info">
                                <h3 class="book-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                                    </a>
                                </h3>

                                <?php if ($collection) : ?>
                                    <div class="book-collection">
                                        <span class="collection-badge">
                                            <span class="dashicons dashicons-book-alt"></span>
                                            <?php echo esc_html($collection->post_title); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($maison_edition) : ?>
                                    <div class="book-publisher">
                                        <span class="publisher-badge">
                                            <span class="dashicons dashicons-building"></span>
                                            <?php echo esc_html($maison_edition->post_title); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <div class="book-meta">
                                    <?php if ($n_sortie) : ?>
                                        <span class="meta-item">
                                            <span class="dashicons dashicons-sort"></span>
                                            N°<?php echo esc_html($n_sortie); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($date_sortie) : ?>
                                        <span class="meta-item">
                                            <span class="dashicons dashicons-calendar-alt"></span>
                                            <?php echo esc_html($date_sortie); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($nombre_pages) : ?>
                                        <span class="meta-item">
                                            <span class="dashicons dashicons-text-page"></span>
                                            <?php echo esc_html($nombre_pages); ?> pages
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($equipe_creative && is_array($equipe_creative) && count($equipe_creative) > 0) : ?>
                                    <div class="book-creative-team">
                                        <span class="team-label">Équipe:</span>
                                        <?php 
                                        // Show only first 2 team members
                                        $displayed_team = array_slice($equipe_creative, 0, 2);
                                        foreach ($displayed_team as $member) : ?>
                                            <span class="team-member">
                                                <?php echo esc_html($member['nom']); ?>
                                                <?php if ($member['role']) : ?>
                                                    <span class="role">(<?php echo esc_html($member['role']); ?>)</span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($equipe_creative) > 2) : ?>
                                            <span class="team-more">+<?php echo count($equipe_creative) - 2; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="book-view-btn">
                                    Voir le livre
                                    <span class="dashicons dashicons-arrow-right-alt"></span>
                                </a>
                            </div>
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
