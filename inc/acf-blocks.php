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

    // Register ACF Blocks
    if (function_exists('acf_register_block')) {
        $blocks = array(
            'space' => 'Space',
            'header-video' => 'Header Video',
            'teaser-list-v1' => 'Teaser List (v1)',
            'line-divider' => 'Line / divider',
            'text' => 'Text',
            'icon-text-teaser' => 'Icon-Text Teaser',
            'post-teaser-list' => 'Post Teaser List',
            'plus-100-gradient-teaser' => 'Plus 100 Gradient Teaser',
        );

        foreach ($blocks as $name => $title) {
            acf_register_block(array(
                'name' => $name,
                'title' => __($title),
                'description' => __("A custom block for displaying {$title}."),
                'render_callback' => 'gl_acf_block_render_callback',
                'category' => 'formatting',
                'icon' => '',
                'mode' => 'edit',
                'keywords' => array($name, 'custom'),
                'supports' => array(
                    'align' => array('wide', 'full'),
                ),
                'align' => 'wide',
            ));
        }
    }
}

function gl_acf_block_render_callback($block) {
    $name = str_replace('acf/', '', $block['name']);
    $template = get_theme_file_path("/components/block-{$name}.php");

    if (file_exists($template)) {
        include $template;
    }
}

function gl_allowed_block_types($allowed_blocks, $post) {
    return array(
        'acf/space',
        'acf/header-video',
        'acf/teaser-list-v1',
        'acf/line-divider',
        'acf/text',
        'acf/icon-text-teaser',
        'acf/post-teaser-list',
        'acf/plus-100-gradient-teaser',
    );
}