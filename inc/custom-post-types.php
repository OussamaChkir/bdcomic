<?php 
/**************************************************
 Register "Collection" Custom Post Type
 **************************************************/
 function register_collection_cpt() {
    $labels = array(
        'name'               => _x('Collections', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Collection', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Collections', 'bdcomic'),
        'all_items'          => __('All Collections', 'bdcomic'),
        'add_new_item'       => __('Add New Collection', 'bdcomic'),
        'edit_item'          => __('Edit Collection', 'bdcomic'),
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
        'menu_icon'          => 'dashicons-book-alt',
    );

    register_post_type('collection', $args);
}
add_action('init', 'register_collection_cpt');
/*
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
add_action('init', 'register_contact_person_taxonomies'); */

/**************************************************
 Register "Artiste" Custom Post Type
 **************************************************/
 function register_artiste_cpt() {
    $labels = array(
        'name'               => _x('Artistes', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Artiste', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Artistes', 'bdcomic'),
        'all_items'          => __('All Artistes', 'bdcomic'),
        'add_new_item'       => __('Add New Artiste', 'bdcomic'),
        'edit_item'          => __('Edit Artiste', 'bdcomic'),
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
        'menu_icon'          => 'dashicons-art',
    );

    register_post_type('artiste', $args);
}
add_action('init', 'register_artiste_cpt');

/**************************************************
 Register "editeur" Custom Post Type
 **************************************************/
 function register_editeur_cpt() {
    $labels = array(
        'name'               => _x('Editeurs', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Editeur', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Editeurs', 'bdcomic'),
        'all_items'          => __('All Editeurs', 'bdcomic'),
        'add_new_item'       => __('Add New Editeur', 'bdcomic'),
        'edit_item'          => __('Edit Editeur', 'bdcomic'),
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
        'menu_icon'          => 'dashicons-building',
    );

    register_post_type('editeur', $args);
}
add_action('init', 'register_editeur_cpt');

/**************************************************
 Register "livre" Custom Post Type
 **************************************************/
 function register_livre_cpt() {
    $labels = array(
        'name'               => _x('Livres', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Livre', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Livres', 'bdcomic'),
        'all_items'          => __('All Livres', 'bdcomic'),
        'add_new_item'       => __('Add New Livre', 'bdcomic'),
        'edit_item'          => __('Edit Livre', 'bdcomic'),
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
        'menu_icon'          => 'dashicons-book',
    );

    register_post_type('livre', $args);
}
add_action('init', 'register_livre_cpt');