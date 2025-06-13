<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="site-logo">
                    <?php $logo = get_field('logo', 'option'); ?>

                    <a href="<?php echo home_url(); ?>">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php elseif ($logo) : ?>
                            <?php echo wp_get_attachment_image( $logo['ID'], 'site-logo', false, array('loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                        <?php else : ?>
                            <?php bloginfo('name'); ?>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <div class="col-md-7">
                <div class="top-header">
                    <div class="meta-menu">
                        <?php if (has_nav_menu('meta-menu')) {
                            wp_nav_menu([
                                'theme_location' => 'meta-menu',
                                'menu_class' => 'nav',
                                'depth' => 1,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </div>

                    <button id="theme-toggle"><?php _e('Contrast', 'korsch'); ?></button>

                    <div class="language-switcher">
                        <ul class="nav">
                            <?php
                                $languages = apply_filters('wpml_active_languages', null);
                                if (!empty($languages)) {
                                    foreach ($languages as $language) {
                                        $active_class = $language['active'] ? 'active' : '';
                                        echo '<li class="nav-item ' . $active_class . '">';
                                            echo '<a href="' . esc_url($language['url']) . '" class="nav-link">';
                                            echo esc_html($language['language_code']);
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        

        <div class="main-header">
            <nav class="main-navigation navbar navbar-expand-lg">
                <button class="navbar-toggler" type="button" data-bs-target="#main-menu-dropdown">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <button type="button" class="btn-close navigation-close" data-bs-target="#main-menu-mobile"></button>

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

    <div id="main-menu-mobile" class="main-menu-dropdown collapse">
        <div class="container">
            <div class="menu-items-children">
                <?php if (has_nav_menu('main-menu')) {
                        wp_nav_menu([
                            'theme_location' => 'main-menu',
                            'menu_class' => 'navbar-nav',
                            'depth' => 3,
                            //'walker' => new WP_Bootstrap_Navwalker_mobile()
                        ]); 
                } ?>

                <div class="top-header top-header-mobile">
                    <!-- <div class="language-switcher">
                        <ul class="nav">
                            <?php
                                $languages = apply_filters('wpml_active_languages', null);
                                if (!empty($languages)) {
                                    foreach ($languages as $language) {
                                        $active_class = $language['active'] ? 'active' : '';
                                        echo '<li class="nav-item ' . $active_class . '">';
                                            echo '<a href="' . esc_url($language['url']) . '" class="nav-link">';
                                            echo esc_html($language['language_code']);
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                }
                            ?>
                        </ul>
                    </div> -->

                    <div class="top-menu">
                        <?php if (has_nav_menu('top-menu')) {
                            wp_nav_menu([
                                'theme_location' => 'top-menu',
                                'menu_class' => 'nav',
                                'depth' => 1,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="menu-overlay"></div>