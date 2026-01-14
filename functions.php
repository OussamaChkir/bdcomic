<?php
require_once('inc/goldland-acf-json.php');
require_once('inc/acf-blocks.php');
require_once('inc/tinymce-setup.php');
require_once('inc/functions.php');
require_once('inc/custom-post-types.php');
require_once('inc/anchor-links.php');
require_once('inc/user-books-management.php');
require_once('inc/admin-book-problems.php');
require_once('inc/admin-statistics.php');
require_once('inc/ajax-handlers.php');

class wbg_theme
{
    // set the option name you use in your settings page
    public static $option_name = 'theme_options';

    public function __construct()
    {
        // theme setup
        add_action('after_setup_theme', array($this, 'setup_theme'));

        // styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'theme_styles_scripts'));

        // include favicons in header
        add_action('wp_head', array($this, 'output_favicon_light'), 5);

        // add admin ajax url into head
        add_action('wp_head', array($this, 'my_ajaxurl'));
    }

    public function setup_theme()
    {
        add_theme_support('custom-logo');

        register_nav_menus(array(
            'meta-menu' => __('Meta Menu', 'bdcomic_theme'),
            'main-menu' => __('Main Menu', 'bdcomic_theme'),
            'footer-menu' => __('Footer Menu', 'bdcomic_theme'),
            'meta-menu-mobile' => __('Meta Menu Mobile', 'bdcomic_theme'),
        ));

        // for multi language support
        // language files under /languages
        load_theme_textdomain('bdcomic_theme', get_template_directory() . '/languages');

        // theme support options
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        // Add support for responsive embeds.
        add_theme_support('responsive-embeds');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // html5 support
        add_theme_support('html5', array(
            'script',
            'style',
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));

        // Enabling custom editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor.css');
    }

    public function theme_styles_scripts()
    {
        if (!is_admin()) {
            // Enqueue styles
            wp_enqueue_style('style', get_template_directory_uri() . '/style.css', false, '1.0', 'screen');
            wp_enqueue_style('print', get_template_directory_uri() . '/assets/css/print.css', false, '1.0', 'print');
            wp_enqueue_style('style-layout', get_template_directory_uri() . '/assets/css/style.css', false, '1.0');

            // Enqueue archive and single page specific styles
            if (is_post_type_archive('artiste')) {
                wp_enqueue_style('archive-artiste', get_template_directory_uri() . '/assets/css/Globals/archive-artiste.css', false, '1.0');
                wp_enqueue_style('archive-search', get_template_directory_uri() . '/assets/css/Globals/archive-search.css', false, '1.0');
            }
            if (is_post_type_archive('collection')) {
                wp_enqueue_style('archive-collection', get_template_directory_uri() . '/assets/css/Globals/archive-collection.css', false, '1.0');
                wp_enqueue_style('archive-search', get_template_directory_uri() . '/assets/css/Globals/archive-search.css', false, '1.0');
            }
            if (is_post_type_archive('editeur')) {
                wp_enqueue_style('archive-editeur', get_template_directory_uri() . '/assets/css/Globals/archive-editeur.css', false, '1.0');
                wp_enqueue_style('archive-search', get_template_directory_uri() . '/assets/css/Globals/archive-search.css', false, '1.0');
            }
            if (is_post_type_archive('livre')) {
                wp_enqueue_style('archive-livre', get_template_directory_uri() . '/assets/css/Globals/archive-livre.css', false, '1.0');
                wp_enqueue_style('archive-search', get_template_directory_uri() . '/assets/css/Globals/archive-search.css', false, '1.0');
            }
            if (is_post_type_archive('guide_lecture')) {
                wp_enqueue_style('archive-guide-lecture', get_template_directory_uri() . '/assets/css/Globals/archive-guide-lecture.css', false, '1.0');
                wp_enqueue_style('archive-search', get_template_directory_uri() . '/assets/css/Globals/archive-search.css', false, '1.0');
            }
            if (is_singular('artiste')) {
                wp_enqueue_style('single-artiste', get_template_directory_uri() . '/assets/css/Globals/single-artiste.css', false, '1.0');
            }
            if (is_singular('collection')) {
                wp_enqueue_style('single-collection', get_template_directory_uri() . '/assets/css/Globals/single-collection.css', false, '1.0');
            }
            if (is_singular('editeur')) {
                wp_enqueue_style('single-editeur', get_template_directory_uri() . '/assets/css/Globals/single-editeur.css', false, '1.0');
            }
            if (is_singular('livre')) {
                wp_enqueue_style('single-livre', get_template_directory_uri() . '/assets/css/Globals/single-livre.css', false, '1.0');
            }
            if (is_singular('guide_lecture')) {
                wp_enqueue_style('single-guide-lecture', get_template_directory_uri() . '/assets/css/Globals/single-guide-lecture.css', false, '1.0');
            }
            if (is_singular('sous_collection')) {
                wp_enqueue_style('single-sous-collection', get_template_directory_uri() . '/assets/css/Globals/single-sous-collection.css', false, '1.0');
            }
            if (is_page_template('page-with-sidebar.php')) {
                wp_enqueue_style('page-with-sidebar', get_template_directory_uri() . '/assets/css/Globals/page-with-sidebar.css', false, '1.0');
            }

            // Enqueue scripts
            wp_enqueue_script('jquery-custom', get_template_directory_uri() . '/assets/plugins/jquery/jquery.js', [], null, true);
            wp_enqueue_script('bootstrap-bundle', get_template_directory_uri() . '/assets/plugins/bootstrap/bootstrap.bundle.min.js', ['jquery-custom'], null, true);

            // wp_enqueue_script('cookieconsent', get_template_directory_uri() . '/assets/plugins/cookieconsent/cookieconsent.umd.js', [], null, true);
            // wp_enqueue_script('cookieconsent-config', get_template_directory_uri() . '/assets/js/cookieconsent-config.js', ['jquery-custom'], false, true);

            // Main script
            wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', ['jquery-custom'], false, true);

            // Archive search script for archive pages
            if (is_post_type_archive()) {
                wp_enqueue_script('archive-search', get_template_directory_uri() . '/assets/js/archive-search.js', ['jquery-custom'], false, true);
                wp_localize_script('archive-search', 'archiveSearchData', array(
                    'nonce' => wp_create_nonce('archive_search_nonce'),
                    'ajaxurl' => admin_url('admin-ajax.php')
                ));
            }

            // Magnifier script for single livre page
            if (is_singular('livre')) {
                wp_enqueue_script('magnifier', get_template_directory_uri() . '/assets/js/magnifier.js', ['jquery-custom'], false, true);
            }
        }
    }

    public function output_favicon_light()
    {
        $path = get_template_directory_uri() . '/assets/img/favicons/light/';
        ?>
        <link data-theme="light" rel="apple-touch-icon" sizes="180x180" href="<?php echo $path . 'apple-touch-icon.png'; ?>">
        <link data-theme="light" rel="icon" type="image/png" sizes="192x192"
            href="<?php echo $path . 'web-app-manifest-192x192.png'; ?>">
        <link data-theme="light" rel="icon" type="image/png" sizes="512x512"
            href="<?php echo $path . 'web-app-manifest-512x512.png'; ?>">
        <link data-theme="light" rel="shortcut icon" href="<?php echo $path . 'favicon.ico'; ?>">
        <link data-theme="light" rel="icon" sizes="16x16 32x32 64x64" href="<?php echo $path . 'favicon.ico'; ?>">
        <link data-theme="light" rel="icon" type="image/png" sizes="96x96" href="<?php echo $path . 'favicon-96x96.png'; ?>">
        <link data-theme="light" rel="icon" type="image/png" sizes="32x32" href="<?php echo $path . 'favicon-32x32.png'; ?>">
        <link data-theme="light" rel="icon" type="image/png" sizes="16x16" href="<?php echo $path . 'favicon-16x16.png'; ?>">
        <link data-theme="light" rel="manifest" href="<?php echo $path . 'site.webmanifest'; ?>" crossorigin="use-credentials">
        <link data-theme="light" rel="mask-icon" href="<?php echo $path . 'favicon.svg'; ?>" color="#5bbad5">
        <meta data-theme="light" name="msapplication-TileColor" content="#f7f7f7">
        <meta data-theme="light" name="theme-color" content="#f7f7f7">
        <meta data-theme="light" name="msapplication-config" content="<?php echo $path . 'browserconfig.xml'; ?>" />
        <?php
    }


    public function my_ajaxurl()
    {
        echo '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
    }
}
new wbg_theme();

/* Allow SVG files config */
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
define('ALLOW_UNFILTERED_UPLOADS', true);

function fix_svg_thumb_display()
{
    echo '     
		<style>         
			td.media-icon img[src$=".svg"],
			img[src$=".svg"].attachment-post-thumbnail {
				width: 100% !important;
				height: auto !important;
				}
		</style>   ';
}
add_action('admin_head', 'fix_svg_thumb_display');

//Admin CSS Override
function sp_admin_style()
{
    wp_register_style('sp_admin_css', get_bloginfo('stylesheet_directory') . '/admin-style.css', false, '1.0.0');
    wp_enqueue_style('sp_admin_css');
}
add_action('admin_enqueue_scripts', 'sp_admin_style');

/* Disable auto save */
add_action('admin_init', 'disable_autosave');
function disable_autosave()
{
    wp_deregister_script('autosave');
}

function load_dashicons_for_users()
{
    // Always load Dashicons on the frontend
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'load_dashicons_for_users');


// Custom Walker Class for adding Bootstrap classes to wp_nav_menu
class WP_Bootstrap_Navwalker extends Walker_Nav_Menu
{

    // Add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $classes = array('dropdown-menu');
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "\n$indent<ul$class_names>\n";
    }

    // Add main/sub classes to li's and links
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item'; // Add class to li element

        // Add class for dropdown
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'dropdown';
        }

        // Add active class to <li> instead of <a>
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $classes[] = 'active';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li' . $class_names . '>'; // <li class="nav-item active">

        $atts = array();
        $atts['class'] = 'nav-link'; // Only nav-link on <a>

        if (in_array('dropdown', $classes)) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
        }

        // Add aria-current only to <a>, not class active
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $atts['aria-current'] = 'page';
        }

        $atts['href'] = !empty($item->url) ? $item->url : '';
        if (!empty($item->target)) {
            $atts['target'] = $item->target;
        }

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

// Flush rewrite rules on theme activation to enable custom post type archives
function bdcomic_flush_rewrite_rules()
{
    // Only flush if we haven't done it before
    if (!get_option('bdcomic_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('bdcomic_rewrite_flushed', true);
    }
}
add_action('after_switch_theme', 'bdcomic_flush_rewrite_rules');

// Also flush rewrite rules when custom post types are registered
function bdcomic_flush_rewrite_rules_on_init()
{
    if (get_option('bdcomic_rewrite_flushed')) {
        delete_option('bdcomic_rewrite_flushed');
        flush_rewrite_rules();
    }
}
add_action('init', 'bdcomic_flush_rewrite_rules_on_init', 20);



function bdcomic_search_query($query)
{
    if ($query->is_search() && !is_admin()) {
        $query->set('post_type', array('post', 'page', 'Collection', 'Artiste', 'Editeur', 'Livre')); // Add your custom types
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts', 'bdcomic_search_query');

function bdcomic_custom_posts_per_page($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    $limits = [
        'collection' => 9,
        'artiste' => 12,
        'livre' => 12,
        'editeur' => 9,
    ];

    foreach ($limits as $post_type => $number) {
        if ($query->is_post_type_archive($post_type)) {
            $query->set('posts_per_page', $number);
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            break;
        }
    }
}
add_action('pre_get_posts', 'bdcomic_custom_posts_per_page');
