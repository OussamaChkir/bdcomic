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
        'publicly_queryable' => false, // disables front-end access
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => false, // disables permalink structure
        'supports'           => array('title'),
        'menu_position' => 20,
        'menu_icon' => 'dashicons-id',
    );

    register_post_type('contact_person', $args);
}
add_action('init', 'register_contact_person_cpt');

// Register "Product Category" Taxonomy
function register_products_taxonomy() {
    $labels = array(
        'name'              => _x('Categories of Product', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Category of Product', 'taxonomy singular name', 'korsch'),
        'all_items'         => __('All Categories of Product', 'korsch'),
        'add_new_item'      => __('Add New Category', 'korsch'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'product-category'),
    );

    register_taxonomy('product_category', 'product', $args);
}
add_action('init', 'register_products_taxonomy');



?>