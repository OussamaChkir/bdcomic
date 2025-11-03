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
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => array('slug' => 'collections'),
        'publicly_queryable' => true,
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
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => array('slug' => 'artistes'),
        'publicly_queryable' => true,
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
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => array('slug' => 'editeurs'),
        'publicly_queryable' => true,
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
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => array('slug' => 'livres'),
        'publicly_queryable' => true,
        'supports'           => array('title'),
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-book',
    );

    register_post_type('livre', $args);
}
add_action('init', 'register_livre_cpt');

/**************************************************
| Register "Guide De Lecture" Custom Post Type
**************************************************/
function register_guide_lecture_cpt() {
    $labels = array(
        'name'               => _x('Guides De Lecture', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Guide De Lecture', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Guides De Lecture', 'bdcomic'),
        'all_items'          => __('All Guides De Lecture', 'bdcomic'),
        'add_new_item'       => __('Add New Guide De Lecture', 'bdcomic'),
        'edit_item'          => __('Edit Guide De Lecture', 'bdcomic'),
        'new_item'           => __('New Guide De Lecture', 'bdcomic'),
        'view_item'          => __('View Guide De Lecture', 'bdcomic'),
        'search_items'       => __('Search Guides De Lecture', 'bdcomic'),
        'not_found'          => __('No Guides De Lecture found', 'bdcomic'),
        'not_found_in_trash' => __('No Guides De Lecture found in Trash', 'bdcomic'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => true,
        'show_in_rest'       => true, // Enable Gutenberg + ACF blocks
        'rewrite'            => array('slug' => 'guides-lecture'),
        'publicly_queryable' => true,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-list-view',
        'capability_type'    => 'post',
        'hierarchical'       => false,
    );

    register_post_type('guide_lecture', $args);
}
add_action('init', 'register_guide_lecture_cpt');

/**************************************************
 Register "Sous Collection" Custom Post Type
 - Fields: Name (title), Image, Date Sortie, Date Fin
 - Relationship: linked to one parent Collection (collection CPT)
 **************************************************/
function register_sous_collection_cpt() {
    $labels = array(
        'name'               => _x('Sous Collections', 'Post Type General Name', 'bdcomic'),
        'singular_name'      => _x('Sous Collection', 'Post Type Singular Name', 'bdcomic'),
        'menu_name'          => __('Sous Collections', 'bdcomic'),
        'all_items'          => __('All Sous Collections', 'bdcomic'),
        'add_new_item'       => __('Add New Sous Collection', 'bdcomic'),
        'edit_item'          => __('Edit Sous Collection', 'bdcomic'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => true,
        'show_in_rest'       => true,
        'rewrite'            => array('slug' => 'sous-collections'),
        'publicly_queryable' => true,
        'supports'           => array('title'),
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-index-card',
    );

register_post_type('sous_collection', $args);
}
add_action('init', 'register_sous_collection_cpt');

// Meta keys
define('SC_META_IMAGE', 'sc_image');
define('SC_META_DATE_SORTIE', 'sc_date_sortie');
define('SC_META_DATE_FIN', 'sc_date_fin');
define('SC_META_PARENT_COLLECTION', 'sc_parent_collection');
define('SC_META_ETAT', 'sc_etat');

// ACF Field Group for Sous Collection
add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) { return; }

    acf_add_local_field_group(array(
        'key' => 'group_sc_fields',
        'title' => __('Sous Collection', 'bdcomic'),
        'fields' => array(
            array(
                'key' => 'field_sc_parent_collection',
                'label' => __('Parent Collection', 'bdcomic'),
                'name' => SC_META_PARENT_COLLECTION,
                'type' => 'post_object',
                'post_type' => array('collection'),
                'return_format' => 'id',
                'ui' => 1,
                'required' => 0,
            ),
            array(
                'key' => 'field_sc_etat',
                'label' => __('État', 'bdcomic'),
                'name' => SC_META_ETAT,
                'type' => 'select',
                'choices' => array(
                    'En Cours' => 'En Cours',
                    'Terminée' => 'Terminée',
                ),
                'allow_null' => 1,
                'ui' => 1,
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_sc_date_sortie',
                'label' => __('Date Sortie', 'bdcomic'),
                'name' => SC_META_DATE_SORTIE,
                'type' => 'date_picker',
                'display_format' => 'Y-m-d',
                'return_format' => 'Y-m-d',
                'required' => 0,
            ),
            array(
                'key' => 'field_sc_date_fin',
                'label' => __('Date Fin', 'bdcomic'),
                'name' => SC_META_DATE_FIN,
                'type' => 'date_picker',
                'display_format' => 'Y-m-d',
                'return_format' => 'Y-m-d',
                'required' => 0,
            ),
            array(
                'key' => 'field_sc_image',
                'label' => __('Image', 'bdcomic'),
                'name' => SC_META_IMAGE,
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'required' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'sous_collection',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 1,
    ));
});

// Admin columns
function sc_columns($columns) {
    $columns['sc_parent'] = __('Collection', 'bdcomic');
    $columns['sc_date_sortie'] = __('Date Sortie', 'bdcomic');
    $columns['sc_date_fin'] = __('Date Fin', 'bdcomic');
    return $columns;
}
add_filter('manage_sous_collection_posts_columns', 'sc_columns');

function sc_custom_column($column, $post_id) {
    switch ($column) {
        case 'sc_parent':
            $parent_id = (int) get_post_meta($post_id, SC_META_PARENT_COLLECTION, true);
            if ($parent_id) {
                echo esc_html(get_the_title($parent_id));
            } else {
                echo '—';
            }
            break;
        case 'sc_date_sortie':
            $v = get_post_meta($post_id, SC_META_DATE_SORTIE, true);
            echo $v ? esc_html($v) : '—';
            break;
        case 'sc_date_fin':
            $v = get_post_meta($post_id, SC_META_DATE_FIN, true);
            echo $v ? esc_html($v) : '—';
            break;
    }
}
add_action('manage_sous_collection_posts_custom_column', 'sc_custom_column', 10, 2);

function sc_sortable_columns($columns) {
    $columns['sc_date_sortie'] = 'sc_date_sortie';
    $columns['sc_date_fin'] = 'sc_date_fin';
    return $columns;
}
add_filter('manage_edit-sous_collection_sortable_columns', 'sc_sortable_columns');

function sc_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('post_type') !== 'sous_collection') return;

    $orderby = $query->get('orderby');
    if ($orderby === 'sc_date_sortie') {
        $query->set('meta_key', SC_META_DATE_SORTIE);
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'sc_date_fin') {
        $query->set('meta_key', SC_META_DATE_FIN);
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'sc_orderby');