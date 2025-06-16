<?php
require_once('inc/goldland-acf-json.php');
require_once('inc/acf-blocks.php');
require_once('inc/tinymce-setup.php');
require_once('inc/functions.php');
// require_once('inc/custom-post-types.php');

class wbg_theme {
    // set the option name you use in your settings page
    public static $option_name = 'theme_options';

    public function __construct() {
        // theme setup
        add_action('after_setup_theme', array($this, 'setup_theme'));

        // styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'theme_styles_scripts'));

        // include favicons in header
        add_action('wp_head', array($this, 'include_favicons'), 5);

        // add admin ajax url into head
        add_action('wp_head', array($this, 'my_ajaxurl'));
    }

    public function setup_theme() {
        add_theme_support('custom-logo');

        register_nav_menus( array(
            'meta-menu'    => __( 'Meta Menu', 'korsch' ),
            'main-menu'   => __( 'Main Menu', 'korsch' ),
            'footer-menu' => __( 'Footer Menu', 'korsch' ),
        ) );

        // for multi language support
        // language files under /languages
        load_theme_textdomain('korsch', get_template_directory() . '/languages');

        // theme support options
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        // Add support for responsive embeds.
        add_theme_support('responsive-embeds');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // html5 support
        add_theme_support('html5', array(
            'script', 'style', 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));        

        // Enabling custom editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor.css');
    }

    public function theme_styles_scripts() {
        if (!is_admin()) {
            // Enqueue styles
            wp_enqueue_style('style', get_template_directory_uri() . '/style.css', false, '1.0', 'screen');
            wp_enqueue_style('print', get_template_directory_uri() . '/assets/css/print.css', false, '1.0', 'print');
            wp_enqueue_style('style-layout', get_template_directory_uri() . '/assets/css/style.css', false, '1.0');

            // Enqueue scripts
            wp_enqueue_script('jquery-custom', get_template_directory_uri() . '/assets/plugins/jquery/jquery.js', [], null, true);
            wp_enqueue_script('bootstrap-bundle', get_template_directory_uri() . '/assets/plugins/bootstrap/bootstrap.bundle.min.js', ['jquery-custom'], null, true);

            wp_enqueue_script('theme-toggle', get_template_directory_uri() . '/assets/js/theme-toggle.js', [], null, true);

            wp_enqueue_script('hoverSlippery', get_template_directory_uri() . '/assets/plugins/hoverslippery/hoverSlippery.min.js', [], null, true);

            // wp_enqueue_script('cookieconsent', get_template_directory_uri() . '/assets/plugins/cookieconsent/cookieconsent.umd.js', [], null, true);
            // wp_enqueue_script('cookieconsent-config', get_template_directory_uri() . '/assets/js/cookieconsent-config.js', ['jquery-custom'], false, true);

            // Main script
            wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', ['jquery-custom'], false, true);
        }
    }

    public function include_favicons() {
        $path = get_template_directory_uri() . '/assets/img/favicons/';
        ?>
            <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $path . 'apple-touch-icon.png'; ?>">
            <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $path . 'web-app-manifest-192x192.png'; ?>">
            <link rel="icon" type="image/png" sizes="512x512" href="<?php echo $path . 'web-app-manifest-512x512.png'; ?>">
            <link rel="shortcut icon" href="<?php echo $path . 'favicon.ico'; ?>">
            <link rel="icon" sizes="16x16 32x32 64x64" href="<?php echo $path . 'favicon.ico'; ?>">
            <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $path . 'favicon-96x96.png'; ?>">
            <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $path . 'favicon-32x32.png'; ?>">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $path . 'favicon-16x16.png'; ?>">
            <link rel="manifest" href="<?php echo $path . 'site.webmanifest'; ?>" crossorigin="use-credentials">
            <link rel="mask-icon" href="<?php echo $path . 'favicon.svg'; ?>" color="#5bbad5">
            <meta name="msapplication-TileColor" content="#f7f7f7">
            <meta name="theme-color" content="#f7f7f7">
            <meta name="msapplication-config" content="<?php echo $path . 'browserconfig.xml'; ?>" />
        <?php
    }

    public function my_ajaxurl() {
        echo '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
    }
}
new wbg_theme();

/* Allow SVG files config */
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
define('ALLOW_UNFILTERED_UPLOADS', true);

function fix_svg_thumb_display() {
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
function sp_admin_style() {
    wp_register_style('sp_admin_css', get_bloginfo('stylesheet_directory') . '/admin-style.css', false, '1.0.0');
    wp_enqueue_style('sp_admin_css');
}
add_action('admin_enqueue_scripts', 'sp_admin_style');

/* Disable auto save */
add_action( 'admin_init', 'disable_autosave' );
function disable_autosave() {
    wp_deregister_script( 'autosave' );
}

// Custom Walker Class for adding Bootstrap classes to wp_nav_menu
class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

    // Add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $classes = array('dropdown-menu');
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "\n$indent<ul$class_names>\n";
    }

    // Add main/sub classes to li's and links
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item'; // Add class to li element

        // Add class for dropdown
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'dropdown';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li' . $class_names .'>';

        $atts = array();
        $atts['class'] = 'nav-link'; // Add class to a element
        if (in_array('dropdown', $classes)) {
            //$atts['class'] .= ' dropdown-toggle';
            //$atts['data-bs-toggle'] = 'collapse';
            //$atts['aria-expanded'] = 'false';
            $atts['data-bs-target'] = '#main-menu-dropdown';
            //$atts['aria-controls'] = 'main-menu-dropdown';
            $atts['data-dropdown-id'] = 'menu-item-' . $item->ID;
        }
        $atts['href']  = !empty($item->url) ? $item->url : '';
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

// Add Cookies link to menu Footer (bottom-menu)
// function add_cookie_link_to_footer_menu($items, $args) {
//     if ($args->theme_location == 'bottom-menu') {
//         $cookie_link = '<li class="menu-item"><a href="#" data-cc="show-preferencesModal">'.__('Cookie-Einstellungen', 'korsch').'</a></li>';
//         $items = $items . $cookie_link;
//     }
//     return $items;
// }
// add_filter('wp_nav_menu_items', 'add_cookie_link_to_footer_menu', 10, 2);


// Get all Contact Form 7 forms : ACF with filed form_select
function populate_cf7_forms_select_field( $field ) {
    $field['choices'] = array();
    
    $cf7_forms = get_posts( array(
        'post_type' => 'wpcf7_contact_form',
        'posts_per_page' => -1
    ) );

    if ( ! empty( $cf7_forms ) ) {
        foreach ( $cf7_forms as $form ) {
            $field['choices'][ $form->ID ] = $form->post_title;
        }
    }

    return $field;
}
add_filter( 'acf/load_field/name=form_select', 'populate_cf7_forms_select_field' );