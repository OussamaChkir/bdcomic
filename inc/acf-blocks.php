<?php
add_action('acf/init', 'gl_acf_init');
add_filter('allowed_block_types', 'gl_allowed_block_types', 10, 2);

function gl_acf_init() {
    // Theme Options
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('Theme Options'),
            'menu_title' => __('Theme Options'),
            'menu_slug'  => 'theme-options',
            'capability' => 'edit_posts',
            'redirect'   => false
        ));
    }

    // Register other blocks
    if (function_exists('acf_register_block')) {

        acf_register_block(array(
            'name' => 'space',
            'title' => __('Space'),
            'description' => __('A custom block for displaying the Space.'),
            'render_callback' => 'gl_acf_block_render_callback',
            'category' => 'formatting',
            'icon' => '',
            'mode' => 'edit',
            'keywords' => array('space', 'custom'),
            'supports' => array(
                'align' => array('wide', 'full'),
                'anchor' => true,
            ),
            'align' => 'wide',
        ));
    }
}

function gl_acf_block_render_callback($block) {
    $name = str_replace('acf/', '', $block['name']);

    if (file_exists(get_theme_file_path("/components/block-{$name}.php"))) {
        include get_theme_file_path("/components/block-{$name}.php");
    }
}

function gl_allowed_block_types($allowed_blocks, $post) {
    if ($post->post_type === 'post') {

        $allowed_blocks = array(
            'acf/space',
        );

    } else if ($post->post_type === 'page') {
        
        $allowed_blocks = array(
            'acf/space',
        );
    }

    return $allowed_blocks;
}