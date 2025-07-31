<?php 
/**************************************************
 Register "Contact Person" Custom Post Type
 **************************************************/
function register_contact_person_cpt() {
    $labels = array(
        'name'               => _x('Contact Persons', 'Post Type General Name', 'korsch'),
        'singular_name'      => _x('Contact Person', 'Post Type Singular Name', 'korsch'),
        'menu_name'          => __('Contact Persons', 'korsch'),
        'all_items'          => __('All Contact Persons', 'korsch'),
        'add_new_item'       => __('Add New Contact Person', 'korsch'),
        'edit_item'          => __('Edit Contact Person', 'korsch'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => false, // disables permalink structure
        'publicly_queryable' => false, // disables front-end access
        'supports'           => array('title'),
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-id',
    );

    register_post_type('contact_person', $args);
}
add_action('init', 'register_contact_person_cpt');

// Register Custom Taxonomy: countries
function register_contact_person_taxonomies() {
    $labels = array(
        'name'              => _x('Regions/Countries', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Country', 'taxonomy singular name', 'korsch'),
        'search_items'      => __('Search Countries', 'korsch'),
        'all_items'         => __('All Countries', 'korsch'),
        'parent_item'       => __('Region', 'korsch'),
        'parent_item_colon' => __('Region:', 'korsch'),
        'edit_item'         => __('Edit', 'korsch'),
        'update_item'       => __('Update', 'korsch'),
        'add_new_item'      => __('Add New', 'korsch'),
        'new_item_name'     => __('New Country Name', 'korsch'),
        'menu_name'         => __('Regions/Countries', 'korsch'),
    );

    $args = array(
        'hierarchical'      => true, // Acts like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => false, // No front-end URL needed
        'publicly_queryable' => false, // disables front-end access
    );

    register_taxonomy('countries', array('contact_person'), $args);
}
add_action('init', 'register_contact_person_taxonomies');



/**************************************************
 Register "Product" Custom Post Type
 **************************************************/
function register_product_cpt() {
    $labels = array(
        'name'               => _x('Products', 'Post Type General Name', 'korsch'),
        'singular_name'      => _x('Product', 'Post Type Singular Name', 'korsch'),
        'menu_name'          => __('Products', 'korsch'),
        'all_items'          => __('All Products', 'korsch'),
        'add_new_item'       => __('Add New Product', 'korsch'),
        'edit_item'          => __('Edit Product', 'korsch'),
        'new_item'           => __('New Product', 'korsch'),
        'view_item'          => __('View Product', 'korsch'),
        'search_items'       => __('Search Products', 'korsch'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => true,
        'show_in_rest'       => true, // Required for Gutenberg + ACF Blocks
        'supports'           => array('title', 'editor', 'thumbnail'),
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-products',
    );

    register_post_type('product', $args);
}
add_action('init', 'register_product_cpt');



/**************************************************
 Register "Download" Custom Post Type
 **************************************************/
function register_download_cpt() {
    $labels = array(
        'name'               => _x('Downloads', 'Post Type General Name', 'korsch'),
        'singular_name'      => _x('Download', 'Post Type Singular Name', 'korsch'),
        'menu_name'          => __('Downloads', 'korsch'),
        'all_items'          => __('All Downloads', 'korsch'),
        'add_new_item'       => __('Add New Download', 'korsch'),
        'edit_item'          => __('Edit Download', 'korsch'),
        'new_item'           => __('New Download', 'korsch'),
        'view_item'          => __('View Download', 'korsch'),
        'search_items'       => __('Search Downloads', 'korsch'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF Blocks
        'rewrite'            => false, // disables permalink structure
        'publicly_queryable' => false, // disables front-end access
        'supports'           => array('title', 'thumbnail'),
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-download',
    );

    register_post_type('download', $args);
}
add_action('init', 'register_download_cpt');

function register_download_taxonomy() {
    $labels = array(
        'name'              => _x('Download Categories', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Download Category', 'taxonomy singular name', 'korsch'),
        'search_items'      => __('Search Download Categories', 'korsch'),
        'all_items'         => __('All Download Categories', 'korsch'),
        'parent_item'       => __('Parent Category', 'korsch'),
        'parent_item_colon' => __('Parent Category:', 'korsch'),
        'edit_item'         => __('Edit Download Category', 'korsch'),
        'update_item'       => __('Update Download Category', 'korsch'),
        'add_new_item'      => __('Add New Download Category', 'korsch'),
        'new_item_name'     => __('New Download Category Name', 'korsch'),
        'menu_name'         => __('Download Categories', 'korsch'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'download-category'),
    );

    register_taxonomy('download_category', array('download'), $args);
}
add_action('init', 'register_download_taxonomy');



/**************************************************
 Register "Event" Custom Post Type
 **************************************************/
function register_event_cpt() {
    $labels = array(
        'name'               => _x('Events', 'Post Type General Name', 'korsch'),
        'singular_name'      => _x('Event', 'Post Type Singular Name', 'korsch'),
        'menu_name'          => __('Events', 'korsch'),
        'all_items'          => __('All Events', 'korsch'),
        'add_new_item'       => __('Add New Event', 'korsch'),
        'edit_item'          => __('Edit Event', 'korsch'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => false, // disables permalink structure
        'publicly_queryable' => false, // disables front-end access
        'supports'           => array('title'),
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-calendar-alt',
    );

    register_post_type('event', $args);
}
add_action('init', 'register_event_cpt');

// Register Custom Taxonomy: location
function register_event_location_taxonomy() {
    $labels = array(
        'name'              => _x('Locations', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Location', 'taxonomy singular name', 'korsch'),
        'search_items'      => __('Search Locations', 'korsch'),
        'all_items'         => __('All Locations', 'korsch'),
        'parent_item'       => __('Location', 'korsch'),
        'parent_item_colon' => __('Location:', 'korsch'),
        'edit_item'         => __('Edit', 'korsch'),
        'update_item'       => __('Update', 'korsch'),
        'add_new_item'      => __('Add New', 'korsch'),
        'new_item_name'     => __('New Type Name', 'korsch'),
        'menu_name'         => __('Locations', 'korsch'),
    );

    $args = array(
        'hierarchical'      => true, // Acts like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => false, // No front-end URL needed
        'publicly_queryable' => false, // disables front-end access
    );

    register_taxonomy('location', array('event'), $args);
}
add_action('init', 'register_event_location_taxonomy');

// Register Custom Taxonomy: type
function register_event_type_taxonomy() {
    $labels = array(
        'name'              => _x('Types', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Type', 'taxonomy singular name', 'korsch'),
        'search_items'      => __('Search Types', 'korsch'),
        'all_items'         => __('All Types', 'korsch'),
        'parent_item'       => __('Type', 'korsch'),
        'parent_item_colon' => __('Type:', 'korsch'),
        'edit_item'         => __('Edit', 'korsch'),
        'update_item'       => __('Update', 'korsch'),
        'add_new_item'      => __('Add New', 'korsch'),
        'new_item_name'     => __('New Type Name', 'korsch'),
        'menu_name'         => __('Types', 'korsch'),
    );

    $args = array(
        'hierarchical'      => true, // Acts like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => false, // No front-end URL needed
        'publicly_queryable' => false, // disables front-end access
    );

    register_taxonomy('type', array('event'), $args);
}
add_action('init', 'register_event_type_taxonomy');
?>