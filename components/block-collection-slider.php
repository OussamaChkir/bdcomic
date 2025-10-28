<?php

// Enqueue Swiper styles from CDN for better performance
if (!wp_style_is('swiper', 'enqueued') && !wp_style_is('swiper', 'done')) {
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0', 'all');
}
wp_enqueue_style('block-collection-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-collection-slider.css', array('swiper'), '1.0', 'all');

// Check if we have collections before enqueuing scripts
$temp_query = new WP_Query(array(
    'post_type' => 'collection',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
));

$has_collections = $temp_query->have_posts();
wp_reset_postdata();

// Add preload hints for CDN resources if we have collections
if ($has_collections) {
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" as="script">';
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style">';
    }, 1);
}

// Only enqueue scripts if we have collections
if ($has_collections) {
    // Enqueue Swiper script from CDN for better performance
    if (!wp_script_is('swiper', 'enqueued') && !wp_script_is('swiper', 'done')) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    }
    wp_enqueue_script('block-collection-slider', get_template_directory_uri() . '/assets/js/block-collection-slider.js', array('swiper'), '1.0', true);
}

// Get ACF fields for slider settings
$block_settings = get_field('collection_slider_settings');
$collections_per_slider = $block_settings['collections_per_slider'] ?? 10;
$slides_to_show = $block_settings['slides_to_show'] ?? 4;
$slides_to_scroll = $block_settings['slides_to_scroll'] ?? 1;
$autoplay = $block_settings['autoplay'] ?? true;
$autoplay_speed = $block_settings['autoplay_speed'] ?? 3000;
$infinite = $block_settings['infinite'] ?? true;
$arrows = $block_settings['arrows'] ?? true;
$dots = $block_settings['dots'] ?? true;
$speed = $block_settings['speed'] ?? 500;
$title = get_field('title') ?: 'Dernières collections ajoutées';
$description = get_field('description') ?: 'Découvrez les nouvelles collections de la base de données';
?>

<?php if ($has_collections) : ?>
<div class="block-collection-slider">
    <div class="container">
        <div class="slider-header">
            <div class="slider-title-section">
                <h2 class="slider-title"><?php echo esc_html($title); ?></h2>
                <p class="slider-description"><?php echo esc_html($description); ?></p>
            </div>
            <div class="slider-actions">
                <a href="<?php echo get_post_type_archive_link('collection'); ?>" class="view-all-collections-btn">
                    Voir toutes les collections
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                </a>
            </div>
        </div>

        <div class="swiper collection-slider" id="collection-slider">
            <div class="swiper-wrapper">
            <?php
            // Query for latest collections with optimized performance
            $collections_query = new WP_Query(array(
                'post_type' => 'collection',
                'posts_per_page' => min($collections_per_slider, 20), // Limit to max 20 posts
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'no_found_rows' => true, // Skip pagination count
                'update_post_meta_cache' => false, // Skip meta cache
                'update_post_term_cache' => false // Skip term cache
            ));

            if ($collections_query->have_posts() && $collections_query->post_count > 0) :
                while ($collections_query->have_posts()) : $collections_query->the_post();
                    // Get ACF fields
                    $logo = get_field('logo_collection');
                    
                    // Fallback: Try to get image using post ID if ACF fails
                    if (!$logo || (!is_array($logo) && !is_numeric($logo))) {
                        $logo = get_field('logo_collection', get_the_ID());
                    }
                    
                    // Handle different ACF return formats (ID vs Array)
                    $logo_url = '';
                    $logo_alt = '';
                    
                    if ($logo) {
                        // If logo is an image ID, get the image array
                        if (is_numeric($logo)) {
                            $logo_array = wp_get_attachment_image_src($logo, 'full');
                            if ($logo_array) {
                                $logo_url = $logo_array[0];
                                $logo_alt = get_post_meta($logo, '_wp_attachment_image_alt', true) ?: get_the_title();
                            }
                        } 
                        // If logo is already an array
                        elseif (is_array($logo) && !empty($logo['url'])) {
                            $logo_url = $logo['url'];
                            $logo_alt = !empty($logo['alt']) ? $logo['alt'] : get_the_title();
                        }
                        // If logo is a URL string
                        elseif (is_string($logo) && filter_var($logo, FILTER_VALIDATE_URL)) {
                            $logo_url = $logo;
                            $logo_alt = get_the_title();
                        }
                    }
                    
                    $titre = get_field('titre_collection') ?: get_field('nom_collection');
                    $date_debut = get_field('date_debut_collection') ?: get_field('date_de_sortie_collection');
                    $date_fin = get_field('date_fin_collection');
                    $statut = get_field('statut_collection') ?: get_field('etat_collection');
                    $resume = get_field('resume_collection');
                    $editeur = get_field('editeur_collection');
                    ?>
                    <div class="swiper-slide collection-slide">
                        <article class="collection-card">
                            <div class="collection-image-wrapper">
                                <a href="<?php the_permalink(); ?>" class="collection-link">
                                    <?php if ($logo_url) : ?>
                                        <img src="<?php echo esc_url($logo_url); ?>" 
                                             alt="<?php echo esc_attr($logo_alt); ?>" 
                                             class="collection-logo"
                                             loading="lazy">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-book-alt"></span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="collection-info">
                                <h3 class="collection-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                                    </a>
                                </h3>

                                <?php if ($editeur) : ?>
                                    <div class="collection-publisher">
                                        <span class="publisher-badge">
                                            <span class="dashicons dashicons-building"></span>
                                            <?php echo esc_html($editeur->post_title); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($statut) : ?>
                                    <div class="collection-status">
                                        <span class="status-badge status-<?php echo esc_attr(strtolower(str_replace(' ', '-', $statut))); ?>">
                                            <?php echo esc_html($statut); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($date_debut || $date_fin) : ?>
                                    <div class="collection-dates">
                                        <?php if ($date_debut) : ?>
                                            <span class="meta-item">
                                                <span class="dashicons dashicons-calendar-alt"></span>
                                                Début: <?php echo esc_html($date_debut); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($date_fin) : ?>
                                            <span class="meta-item">
                                                <span class="dashicons dashicons-calendar-alt"></span>
                                                Fin: <?php echo esc_html($date_fin); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($resume) : ?>
                                    <div class="collection-summary">
                                        <?php echo wp_trim_words($resume, 20, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="collection-view-btn">
                                    Voir la collection
                                    <span class="dashicons dashicons-arrow-right-alt"></span>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-collections-message">
                    <p>Aucune collection trouvée.</p>
                </div>
            <?php endif; ?>
            </div>
            
            <!-- Navigation buttons -->
            <?php if ($arrows) : ?>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if ($dots) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Localize script with slider settings
wp_localize_script('block-collection-slider', 'collectionSliderData', array(
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

