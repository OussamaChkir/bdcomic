<?php
/**
 * Missing Read Books Grid Block
 * ACF Block wrapper for the Missing Read Books Grid component
 * 
 * @package bdcomic_theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue styles
wp_enqueue_style('block-missing-albums-grid', get_template_directory_uri() . '/assets/css/ContentElements/ce-missing-albums-grid.css', array(), '1.0', 'all');

// Create a unique ID for this block instance
$block_id = 'missing-read-books-grid-' . uniqid();
?>

<div id="<?php echo esc_attr($block_id); ?>" class="block-missing-albums-grid block-missing-read-books-grid">
    <?php
    // Include the main component
    get_template_part('components/missing-read-books-grid');
    ?>
</div>