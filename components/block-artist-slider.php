<?php

// Enqueue Swiper styles from CDN for better performance
if (!wp_style_is('swiper', 'enqueued') && !wp_style_is('swiper', 'done')) {
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0', 'all');
}
wp_enqueue_style('block-artist-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-artist-slider.css', array('swiper'), '1.0', 'all');

// Check if we have artists before enqueuing scripts
$temp_query = new WP_Query(array(
    'post_type' => 'artiste',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => 'photo_artiste',
            'compare' => 'EXISTS'
        )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
));

$has_artists = $temp_query->have_posts();
wp_reset_postdata();

// Add preload hints for CDN resources if we have artists
if ($has_artists) {
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" as="script">';
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style">';
    }, 1);
}

// Only enqueue scripts if we have artists
if ($has_artists) {
    // Enqueue Swiper script from CDN for better performance
    if (!wp_script_is('swiper', 'enqueued') && !wp_script_is('swiper', 'done')) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    }
    wp_enqueue_script('block-artist-slider', get_template_directory_uri() . '/assets/js/block-artist-slider.js', array('swiper'), '1.0', true);
}

// Get ACF fields for slider settings
$block_settings = get_field('artist_slider_settings');
$artists_per_slider = $block_settings['artists_per_slider'] ?? 10;
$slides_to_show = $block_settings['slides_to_show'] ?? 4;
$slides_to_scroll = $block_settings['slides_to_scroll'] ?? 1;
$autoplay = $block_settings['autoplay'] ?? true;
$autoplay_speed = $block_settings['autoplay_speed'] ?? 3000;
$infinite = $block_settings['infinite'] ?? true;
$arrows = $block_settings['arrows'] ?? true;
$dots = $block_settings['dots'] ?? true;
$speed = $block_settings['speed'] ?? 500;
?>

<?php if ($has_artists) : ?>
<div class="block-artist-slider">
    <div class="container">
        <div class="slider-header">
            <div class="slider-title-section">
                <h2 class="slider-title">Derniers artistes ajoutés</h2>
                <p class="slider-description">Découvrez les nouveaux artistes de la base de données</p>
            </div>
            <div class="slider-actions">
                <a href="<?php echo get_post_type_archive_link('artiste'); ?>" class="view-all-artists-btn">
                    Voir tous les artistes
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                </a>
            </div>
        </div>

        <div class="swiper artist-slider" id="artist-slider">
            <div class="swiper-wrapper">
            <?php
            // Query for latest artists with optimized performance
            $artists_query = new WP_Query(array(
                'post_type' => 'artiste',
                'posts_per_page' => min($artists_per_slider, 20), // Limit to max 20 posts
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'key' => 'photo_artiste',
                        'compare' => 'EXISTS'
                    )
                ),
                'no_found_rows' => true, // Skip pagination count
                'update_post_meta_cache' => false, // Skip meta cache
                'update_post_term_cache' => false // Skip term cache
            ));

            if ($artists_query->have_posts() && $artists_query->post_count > 0) :
                while ($artists_query->have_posts()) : $artists_query->the_post();
                    // Get ACF fields
                    $photo = get_field('photo_artiste');
                    $nom = get_field('nom_artiste');
                    $prenom = get_field('prenom_artiste');
                    $nom_dartiste = get_field('nom_dartiste');
                    $date_naissance = get_field('date_de_naissance_artiste');
                    $deces = get_field('deces');
                    $nationalite = get_field('nationalite_artiste');
                    $roles = get_field('roles_artiste');
                    $biographie = get_field('biographie_artiste');
                    ?>

                    <div class="swiper-slide artist-slide">
                        <article class="artist-card">
                            <div class="artist-image-wrapper">
                                <?php if ($photo) : ?>
                                    <a href="<?php the_permalink(); ?>" class="artist-link">
                                        <img src="<?php echo esc_url($photo['url']); ?>" 
                                             alt="<?php echo esc_attr($photo['alt']); ?>" 
                                             class="artist-photo">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="artist-link">
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-admin-users"></span>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="artist-info">
                                <h3 class="artist-name">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        if ($nom_dartiste) {
                                            echo esc_html($nom_dartiste);
                                        } elseif ($nom && $prenom) {
                                            echo esc_html($prenom . ' ' . $nom);
                                        } else {
                                            echo get_the_title();
                                        }
                                        ?>
                                    </a>
                                </h3>

                                <?php if ($roles && is_array($roles)) : ?>
                                    <div class="artist-roles">
                                        <?php 
                                        // Show only first 2 roles
                                        $displayed_roles = array_slice($roles, 0, 2);
                                        foreach ($displayed_roles as $role) : ?>
                                            <span class="role-badge"><?php echo esc_html($role); ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($roles) > 2) : ?>
                                            <span class="role-badge role-more">+<?php echo count($roles) - 2; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($nationalite) : ?>
                                    <div class="artist-nationality">
                                        <span class="nationality-badge"><?php echo esc_html($nationalite); ?></span>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="artist-view-btn">
                                    Voir le profil
                                    <span class="dashicons dashicons-arrow-right-alt"></span>
                                </a>
                            </div>
                        </article>
                    </div>

                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-artists-message">
                    <p>Aucun artiste trouvé.</p>
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
wp_localize_script('block-artist-slider', 'artistSliderData', array(
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

