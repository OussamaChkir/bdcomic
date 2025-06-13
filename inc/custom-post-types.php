<?php 
/**************************************************
 Register "Application Domains" Custom Post Type
 **************************************************/
function register_domains_post_type() {
    $labels = array(
        'name'               => _x('Application Domains', 'Post Type General Name', 'degesa'),
        'singular_name'      => _x('Application Domain', 'Post Type Singular Name', 'degesa'),
        'menu_name'          => __('Application Domains', 'degesa'),
        'all_items'          => __('All Application Domains', 'degesa'),
        'add_new_item'       => __('Add New Application Domain', 'degesa'),
        'edit_item'          => __('Edit Application Domain', 'degesa'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true, // Enable Gutenberg editor for this post type (ACF Blocks)
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'            => array('slug' => 'anwendungsbereich', 'with_front' => false),
    );

    register_post_type('domains', $args);
}
add_action('init', 'register_domains_post_type');

// Register "Domain Category" Taxonomy
function register_domains_taxonomy() {
    $labels = array(
        'name'              => _x('Categories of Application Domain', 'taxonomy general name', 'degesa'),
        'singular_name'     => _x('Category of Application Domain', 'taxonomy singular name', 'degesa'),
        'all_items'         => __('All Categories of Application Domain', 'degesa'),
        'add_new_item'      => __('Add New Category', 'degesa'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'anwendungsbereiche'),
    );

    register_taxonomy('domain_category', 'domains', $args);
}
add_action('init', 'register_domains_taxonomy');

// Enforce single category selection via JavaScript
function restrict_single_domain_category_selection() {
    global $pagenow;
    if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let taxonomyBox = document.querySelector('#domain_categorydiv input[type="checkbox"]');
                if (taxonomyBox) {
                    document.querySelectorAll('#domain_categorydiv input[type="checkbox"]').forEach(function (checkbox) {
                        checkbox.addEventListener('change', function () {
                            document.querySelectorAll('#domain_categorydiv input[type="checkbox"]').forEach(function (cb) {
                                if (cb !== checkbox) {
                                    cb.checked = false;
                                }
                            });
                        });
                    });
                }
            });
        </script>
        <?php
    }
}
add_action('admin_footer', 'restrict_single_domain_category_selection');


/**************************************************
 Register "Products" Custom Post Type
 **************************************************/
function register_products_post_type() {
    $labels = array(
        'name'               => _x('Products', 'Post Type General Name', 'degesa'),
        'singular_name'      => _x('Product', 'Post Type Singular Name', 'degesa'),
        'menu_name'          => __('Products', 'degesa'),
        'all_items'          => __('All Products', 'degesa'),
        'add_new_item'       => __('Add New Product', 'degesa'),
        'edit_item'          => __('Edit Product', 'degesa'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-cart',
        'show_in_rest'       => true, // Enable Gutenberg editor for this post type (ACF Blocks)
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'            => array('slug' => 'produkt', 'with_front' => false),
    );

    register_post_type('products', $args);
}
add_action('init', 'register_products_post_type');

// Register "Product Category" Taxonomy
function register_products_taxonomy() {
    $labels = array(
        'name'              => _x('Categories of Product', 'taxonomy general name', 'degesa'),
        'singular_name'     => _x('Category of Product', 'taxonomy singular name', 'degesa'),
        'all_items'         => __('All Categories of Product', 'degesa'),
        'add_new_item'      => __('Add New Category', 'degesa'),
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


/**************************************************
 Register "Projects" Custom Post Type
 **************************************************/
function register_projects_post_type() {
    $labels = array(
        'name'               => _x('Projects', 'Post Type General Name', 'degesa'),
        'singular_name'      => _x('Project', 'Post Type Singular Name', 'degesa'),
        'menu_name'          => __('Projects', 'degesa'),
        'all_items'          => __('All Projects', 'degesa'),
        'add_new_item'       => __('Add New Project', 'degesa'),
        'edit_item'          => __('Edit Project', 'degesa'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true, // Enable Gutenberg editor for this post type (ACF Blocks)
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'            => array('slug' => 'project', 'with_front' => false),
    );

    register_post_type('projects', $args);
}
add_action('init', 'register_projects_post_type');

// Register "Project Category" Taxonomy
function register_projects_taxonomy() {
    $labels = array(
        'name'              => _x('Categories of Project', 'taxonomy general name', 'degesa'),
        'singular_name'     => _x('Category of Project', 'taxonomy singular name', 'degesa'),
        'all_items'         => __('All Categories of Project', 'degesa'),
        'add_new_item'      => __('Add New Category', 'degesa'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'project-category'),
    );

    register_taxonomy('project_category', 'projects', $args);
}
add_action('init', 'register_projects_taxonomy');

?>