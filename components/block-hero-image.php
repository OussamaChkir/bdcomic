<?php

// Enqueue Swiper styles from CDN for better performance
if (!wp_style_is('swiper', 'enqueued') && !wp_style_is('swiper', 'done')) {
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0', 'all');
}
wp_enqueue_style('block-hero-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-hero-image.css', array('swiper'), '1.0', 'all');

// Get ACF fields for slider settings
$block_settings = get_field('hero_image_settings');
$hero_slides = get_field('hero_slides') ?? array();

// Check if we have slides before enqueuing scripts
$has_slides = !empty($hero_slides) && is_array($hero_slides) && count($hero_slides) > 0;

// Add preload hints for CDN resources if we have slides
if ($has_slides) {
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" as="script">';
        echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style">';
    }, 1);
}

// Only enqueue scripts if we have slides
if ($has_slides) {
    // Enqueue Swiper script from CDN for better performance
    if (!wp_script_is('swiper', 'enqueued') && !wp_script_is('swiper', 'done')) {
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    }
    wp_enqueue_script('block-hero-image', get_template_directory_uri() . '/assets/js/block-hero-image.js', array('swiper'), '1.0', true);
}

// Get slider settings with defaults
$autoplay = $block_settings['autoplay'] ?? true;
$autoplay_speed = $block_settings['autoplay_speed'] ?? 5000;
$effect = $block_settings['effect'] ?? 'fade';
$arrows = $block_settings['arrows'] ?? true;
$dots = $block_settings['dots'] ?? true;
$loop = $block_settings['loop'] ?? true;
$speed = $block_settings['speed'] ?? 600;
$height = $block_settings['height'] ?? '600px';
?>

<?php if ($has_slides) : ?>
<div class="block-hero-image">
    <div class="swiper hero-slider" id="hero-slider" style="--hero-height: <?php echo esc_attr($height); ?>;">
        <div class="swiper-wrapper">
            <?php foreach ($hero_slides as $slide) : 
                $image = $slide['image'] ?? null;
                $title = $slide['title'] ?? '';
                $description = $slide['description'] ?? '';
                $button_text = $slide['button_text'] ?? '';
                $button_link = $slide['button_link'] ?? '';
                $overlay_opacity = $slide['overlay_opacity'] ?? 0.4;
                $text_position = $slide['text_position'] ?? 'center-center';
                $text_alignment = $slide['text_alignment'] ?? 'center';
            ?>
                <div class="swiper-slide hero-slide" 
                     data-text-position="<?php echo esc_attr($text_position); ?>"
                     data-text-alignment="<?php echo esc_attr($text_alignment); ?>">
                    <?php if ($image) : ?>
                        <div class="hero-slide-bg" style="background-image: url('<?php echo esc_url($image['url']); ?>');">
                            <div class="hero-overlay" style="opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="hero-slide-content">
                        <?php if ($title) : ?>
                            <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                        <?php endif; ?>
                        
                        <?php if ($description) : ?>
                            <div class="hero-description"> <?php echo ($description); ?> </div>
                        <?php endif; ?>
                        
                        <?php if ($button_text && $button_link) : ?>
                            <a href="<?php echo esc_url($button_link); ?>" class="hero-button">
                                <?php echo esc_html($button_text); ?>
                                <span class="dashicons dashicons-arrow-right-alt"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($arrows) : ?>
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        <?php endif; ?>
        
        <?php if ($dots) : ?>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>

<?php
// Localize script with slider settings
wp_localize_script('block-hero-image', 'heroImageData', array(
    'autoplay' => $autoplay,
    'autoplaySpeed' => $autoplay_speed,
    'effect' => $effect,
    'arrows' => $arrows,
    'dots' => $dots,
    'loop' => $loop,
    'speed' => $speed
));
?>
<?php endif; ?>

