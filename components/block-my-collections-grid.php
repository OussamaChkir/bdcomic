<?php
/**
 * My Collections Grid Block
 * ACF Block wrapper for the My Collections Grid component
 * 
 * @package bdcomic_theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block settings
$title = get_field('collections_title');
$description = get_field('collections_description');
$show_statistics = get_field('show_statistics');
$show_search = get_field('show_search');
$books_per_row = get_field('books_per_row');
$show_collection_logos = get_field('show_collection_logos');
$show_status_icons = get_field('show_status_icons');

// Set defaults if fields are not set
$title = $title ?: __('Mes Collections', 'bdcomic_theme');
$show_statistics = $show_statistics !== false ? $show_statistics : true;
$show_search = $show_search !== false ? $show_search : true;
$books_per_row = $books_per_row ?: '4';
$show_collection_logos = $show_collection_logos !== false ? $show_collection_logos : true;
$show_status_icons = $show_status_icons !== false ? $show_status_icons : true;

// Create a unique ID for this block instance
$block_id = 'my-collections-grid-' . uniqid();
?>

<div id="<?php echo esc_attr($block_id); ?>" class="my-collections-grid-block">
    <?php
    // Include the main component
    get_template_part('components/my-collections-grid');
    ?>
</div>

<style>
/* Block-specific styles if needed */
.my-collections-grid-block {
    margin: 2rem 0;
}
</style>
