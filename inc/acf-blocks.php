<<<<<<< Updated upstream
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
            'text' => 'Text',
            'register' => 'Register',
            'login' => 'Login',
            'profile' => 'Profile',
            'book-grid' => 'Book Grid',
            'whislisted-book-grid' => 'Whislisted Book Grid',
            'missing-albums-grid' => 'Missing Albums Grid',
            'my-collections-grid' => 'My Collections Grid',
            'artist-slider' => 'Artist Slider',
            'book-slider' => 'Book Slider',
            'collection-slider' => 'Collection Slider',
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
        'acf/text',
        'acf/register',
        'acf/profile',
        'acf/login',
        'acf/book-grid',
        'acf/whislisted-book-grid',
        'acf/missing-albums-grid',
        'acf/my-collections-grid',
        'acf/artist-slider',
        'acf/book-slider',
        'acf/collection-slider',
    );

    return $allowed_blocks;
=======
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
            'text' => 'Text',
            'register' => 'Register',
            'login' => 'Login',
            'profile' => 'Profile',
            'book-grid' => 'Book Grid',
            'whislisted-book-grid' => 'Whislisted Book Grid',
            'missing-albums-grid' => 'Missing Albums Grid',
            'my-collections-grid' => 'My Collections Grid',
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
        'acf/text',
        'acf/register',
        'acf/profile',
        'acf/login',
        'acf/book-grid',
        'acf/whislisted-book-grid',
        'acf/missing-albums-grid',
        'acf/my-collections-grid',
    );

    return $allowed_blocks;
>>>>>>> Stashed changes
}