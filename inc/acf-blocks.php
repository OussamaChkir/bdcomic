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
            'header-image' => 'Header Image',
            'header-video' => 'Header Video',
            'line-divider' => 'Line / divider',
            'text' => 'Text',
            'icon-text-teaser' => 'Icon-Text Teaser',
            'text-image' => 'Text with Image',
            'text-bild-teaser-mit-button' => 'Text-Bild Teaser mit Button',
            'teaser-list-v1' => 'Teaser List (v1)',
            'teaser-list-v2' => 'Teaser List (v2)',
            'teaser-list-slider' => 'Teaser List / Slider',
            'teaser-slider' => 'Teaser Slider',
            'accordeon' => 'Accordeon',
            'colored-tiles-teaser' => 'Colored Tiles Teaser',
            'plus-100-gradient-teaser' => 'Plus 100 Gradient Teaser',
            'visual-images' => 'Visual Images',
            'logo-wall' => 'Logo Wall',
            'product-image' => 'Product Image',
            'post-teaser-list' => 'Post Teaser List',
            'product-details-table' => 'Product Details Table',
            'blog-post' => 'Blog Post',
            'newsletter' => 'Newsletter',
            'centers-list' => 'Centers List',
            'salesforce-contact-form' => 'Salesforce Contact Form',
            'downloads' => 'Downloads',
            'contact-persons' => 'Contact Persons',
            'event-list' => 'Event List',
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
    $allowed_blocks = array(
        'acf/space',
        'acf/header-image',
        'acf/header-video',
        'acf/line-divider',
        'acf/text',
        'acf/icon-text-teaser',
        'acf/text-image',
        'acf/text-bild-teaser-mit-button',
        'acf/teaser-list-v1',
        'acf/teaser-list-v2',
        'acf/teaser-list-slider',
        'acf/teaser-slider',
        'acf/accordeon',
        'acf/colored-tiles-teaser',
        'acf/plus-100-gradient-teaser',
        'acf/visual-images',
        'acf/logo-wall',
        'acf/product-image',
        'acf/post-teaser-list',
        'acf/blog-post',
        'acf/newsletter',
        'acf/centers-list',
        'acf/salesforce-contact-form',
        'acf/downloads',
        'acf/contact-persons',
        'acf/event-list',
    );

    if ($post->post_type === 'product') {
        $template = get_page_template_slug($post);

        if ($template === '' || $template === 'single-product.php') {
            if (!in_array('acf/product-details-table', $allowed_blocks)) {
                $allowed_blocks[] = 'acf/product-details-table';
            }
        }
    }

    return $allowed_blocks;
}