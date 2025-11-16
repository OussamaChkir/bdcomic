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
            <div class="top-header d-flex align-items-center">
                    <!-- Global Search Form -->
                <div class="header-search">
                 <?php get_search_form();
                 $count_books = wp_count_posts('livre')->publish; ?>
                </div>
                <div class="nbre-livre">
                <i class="dashicons dashicons-book"></i> <?php echo esc_html($count_books) ; ?>
                </div>
            </div>
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
                                'depth' => 2,
                                'walker' => new WP_Bootstrap_Navwalker()
                            ]); 
                        } ?>
                    </div>
                </nav>


            </div>
                <!-- User Actions Container -->
                <div class="header-actions-container">
                    <?php if (is_user_logged_in()) :
                        $user_id = get_current_user_id();
                        $profile_pic = get_avatar_url( $user_id );
                        
                        ?>
                        <!-- Logged in user dropdown menu -->
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                
                            <img src="<?php echo esc_url( $profile_pic ); ?>" alt="Profile" style="width:30px; height:30px; border-radius:50%;">

                                <span class="user-name"><?php echo wp_get_current_user()->display_name; ?></span>
                                <i class="dashicons dashicons-arrow-down-alt2"></i>
                            </button>
                            <ul class="dropdown-menu user-dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo home_url('/ma-bibliotheque'); ?>">
                                    <i class="dashicons dashicons-book"></i> Ma Bibliothèque
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo home_url('/ma-collection'); ?>">
                                    <i class="dashicons dashicons-portfolio"></i> Ma Collection
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo home_url('/mes-souhaits'); ?>">
                                    <i class="dashicons dashicons-heart"></i> Mes Souhaits
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo home_url('/mes-albums-manquants'); ?>">
                                    <i class="dashicons dashicons-search"></i> Mes Albums Manquants
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo wp_logout_url(home_url()); ?>">
                                    <i class="dashicons dashicons-exit"></i> Déconnexion
                                </a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <!-- Non-logged in user login dropdown -->
                        <div class="login-dropdown">
                            <button class="login-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="dashicons dashicons-admin-users"></i>
                                <span>Connexion</span>
                                <i class="dashicons dashicons-arrow-down-alt2"></i>
                            </button>
                            <div class="dropdown-menu login-dropdown-menu">
                                <div class="login-form-container">
                                    <?php if (class_exists('UM')) : ?>
                                        <?php echo do_shortcode('[ultimatemember form_id="42"]'); ?>
                                    <?php else : ?>
                                        <div class="alert alert-warning">
                                            <?php _e('Ultimate Member plugin is not active. Please install and activate the Ultimate Member plugin to display the login form.', 'bdcomic_theme'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                            'depth' => 2,
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