<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
<?php
    $logo = get_field('logo', 'option');
        $logo_light = $logo['logo_light'];
        $logo_dark = $logo['logo_dark'];
    $show_language_switcher = get_field('show_language_switcher', 'option');
?>


<header>
    <div class="container">
        <div class="header-container">
            <div class="site-logo">
                <a href="<?php echo home_url(); ?>">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php elseif ($logo_light || $logo_dark) : ?>
                        <?php if ($logo_light) : ?>
                            <?php echo wp_get_attachment_image( $logo_light['ID'], 'full', false, array('class' => 'logo logo-light','loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                        <?php endif; ?>
                        <?php if ($logo_dark) : ?>
                            <?php echo wp_get_attachment_image( $logo_dark['ID'], 'full', false, array('class' => 'logo logo-dark','loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php bloginfo('name'); ?>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-container">
                <div class="top-header">
                    <nav class="meta-menu">
                        <?php if (has_nav_menu('meta-menu')) {
                            wp_nav_menu([
                                'theme_location' => 'meta-menu',
                                'menu_class' => 'nav',
                                'depth' => 1,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </nav>

                    <button id="theme-toggle" class="icon-contrast"><?php _e('Contrast', 'korsch'); ?></button>

                    <?php if ($show_language_switcher) : ?>
                        <nav class="language-switcher">
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
                        </nav>
                    <?php endif; ?>
                </div>

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
</header>