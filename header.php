<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
    // Get logo from ACF options
    $header_logo = get_field('header_logo', 'option');
?>

    <header id="masthead" class="site-header">
        <div class="container">
        <div class="header-container">
             <div class="site-logo">
                <a href="<?php echo home_url(); ?>">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php elseif ($header_logo) : ?>
                        <?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array('class' => 'logo logo-header','loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                    <?php else : ?>
                        <?php bloginfo('name'); ?>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-container">
                <nav class="main-navigation navbar navbar-expand-xl underline">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-menu-mobile" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="main-navigation">
                        <?php if (has_nav_menu('main-menu')) {
                            wp_nav_menu([
                                'theme_location' => 'main-menu',
                                'menu_class' => 'navbar-nav menu-main-navigation',
                                'depth' => 1,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </div>
                </nav>
            </div>
        </div>
        </div>

        <div id="main-menu-mobile" class="main-menu-dropdown collapse">
            <div class="container">
                <div class="menu-items-children">
                    <?php if (has_nav_menu('main-menu')) {
                        wp_nav_menu([
                            'theme_location' => 'main-menu',
                            'menu_class' => 'navbar-nav',
                            'depth' => 1,
                            'walker' => new WP_Bootstrap_Navwalker()
                        ]); 
                    } ?>

                <div class="bottom-header-mobile">
                    <nav class="meta-menu-mobile">
                        <?php if (has_nav_menu('meta-menu-mobile')) {
                            wp_nav_menu([
                                'theme_location' => 'meta-menu-mobile',
                                'menu_class' => 'nav',
                                'depth' => 1,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    </div>
    </header>