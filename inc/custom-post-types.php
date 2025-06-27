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
        'name'              => _x('Countries', 'taxonomy general name', 'korsch'),
        'singular_name'     => _x('Country', 'taxonomy singular name', 'korsch'),
        'search_items'      => __('Search Countries', 'korsch'),
        'all_items'         => __('All Countries', 'korsch'),
        'parent_item'       => __('Region', 'korsch'),
        'parent_item_colon' => __('Region:', 'korsch'),
        'edit_item'         => __('Edit', 'korsch'),
        'update_item'       => __('Update', 'korsch'),
        'add_new_item'      => __('Add New', 'korsch'),
        'new_item_name'     => __('New Country Name', 'korsch'),
        'menu_name'         => __('Countries', 'korsch'),
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

?>