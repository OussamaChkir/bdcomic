<?php
/**
 * Template Name: Page with Sidebar
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <aside class="page-sidebar">
                    <div class="sidebar-content">
                        <?php
                        // Get sidebar menu items from ACF fields
                        $sidebar_menu_items = get_field('sidebar_menu_items');

                        if ($sidebar_menu_items):
                            ?>
                            <nav class="sidebar-menu">
                                <ul class="sidebar-menu-list">
                                    <?php foreach ($sidebar_menu_items as $item):
                                        $menu_item = $item['menu_item'];
                                        $menu_type = $menu_item['menu_type'];
                                        $custom_title = $menu_item['custom_title'];
                                        $custom_url = $menu_item['custom_url'];
                                        $page_link = $menu_item['page_link'];

                                        // Determine the URL and title
                                        $url = '#';
                                        $title = '';
                                        $icon = '';

                                        switch ($menu_type) {
                                            case 'ma_bibliotheque':
                                                $url = get_permalink(get_page_by_path('ma-bibliotheque'));
                                                $title = $custom_title ?: __('Ma Bibliothèque', 'bdcomic_theme');
                                                $icon = 'dashicons-book-alt';
                                                break;
                                            case 'ma_collection':
                                                $url = get_permalink(get_page_by_path('ma-collection'));
                                                $title = $custom_title ?: __('Ma Collection', 'bdcomic_theme');
                                                $icon = 'dashicons-portfolio';
                                                break;
                                            case 'mes_souhaits':
                                                $url = get_permalink(get_page_by_path('mes-souhaits'));
                                                $title = $custom_title ?: __('Mes Souhaits', 'bdcomic_theme');
                                                $icon = 'dashicons-heart';
                                                break;

                                            case 'custom':
                                                $url = $custom_url;
                                                $title = $custom_title;
                                                $icon = $menu_item['custom_icon'] ?: 'dashicons-admin-links';
                                                break;
                                            case 'page':
                                                $url = get_permalink($page_link);
                                                $title = $custom_title ?: get_the_title($page_link);
                                                $icon = $menu_item['custom_icon'] ?: 'dashicons-admin-page';
                                                break;
                                        }

                                        // Check if current page is active
                                        $is_active = false;
                                        if ($menu_type === 'page' && $page_link && is_page($page_link)) {
                                            $is_active = true;
                                        } elseif ($menu_type === 'custom' && $custom_url && (is_page() && get_permalink() === $custom_url)) {
                                            $is_active = true;
                                        }

                                        $active_class = $is_active ? 'active' : '';
                                        ?>
                                        <li class="sidebar-menu-item <?php echo $active_class; ?>">
                                            <a href="<?php echo esc_url($url); ?>" class="sidebar-menu-link">
                                                <span class="menu-icon dashicons <?php echo esc_attr($icon); ?>"></span>
                                                <span class="menu-text"><?php echo esc_html($title); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        <?php else: ?>
                            <!-- Default menu if no ACF fields are set -->
                            <nav class="sidebar-menu">
                                <ul class="sidebar-menu-list">
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('ma-bibliotheque')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-book-alt"></span>
                                            <span class="menu-text"><?php _e('Ma Bibliothèque', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('ma-collection')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-portfolio"></span>
                                            <span class="menu-text"><?php _e('Ma Collection', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('mes-souhaits')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-heart"></span>
                                            <span class="menu-text"><?php _e('Mes Souhaits', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('mes-albums-manquants')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-search"></span>
                                            <span
                                                class="menu-text"><?php _e('Mes Albums Manquants', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('mes-albums-a-lire')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-book"></span>
                                            <span
                                                class="menu-text"><?php _e('Mes Albums à Lire', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a href="<?php echo get_permalink(get_page_by_path('account')); ?>"
                                            class="sidebar-menu-link">
                                            <span class="menu-icon dashicons dashicons-admin-users"></span>
                                            <span class="menu-text"><?php _e('Mon Compte', 'bdcomic_theme'); ?></span>
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                        <?php endif; ?>

                        <!-- User Books Statistics (if user is logged in) -->
                        <?php if (is_user_logged_in()):
                            $current_user = wp_get_current_user();
                            $user_stats = get_user_books_stats($current_user->ID);
                            $list_labels = get_list_type_labels();
                            ?>
                            <div class="sidebar-stats">
                                <h4 class="sidebar-stats-title"><?php _e('Mes Statistiques', 'bdcomic_theme'); ?></h4>
                                <div class="sidebar-stats-grid">
                                    <div class="sidebar-stat-item">
                                        <span class="stat-number"><?php echo $user_stats['owned_books']; ?></span>
                                        <span class="stat-label"><?php echo $list_labels['owned']; ?></span>
                                    </div>
                                    <div class="sidebar-stat-item">
                                        <span class="stat-number"><?php echo $user_stats['read_books']; ?></span>
                                        <span class="stat-label"><?php echo $list_labels['read']; ?></span>
                                    </div>
                                    <div class="sidebar-stat-item">
                                        <span class="stat-number"><?php echo $user_stats['wishlist_books']; ?></span>
                                        <span class="stat-label"><?php echo $list_labels['wishlist']; ?></span>
                                    </div>
                                    <div class="sidebar-stat-item">
                                        <span class="stat-number"><?php echo $user_stats['loaned_books']; ?></span>
                                        <span class="stat-label"><?php echo $list_labels['loaned']; ?></span>
                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="page-content">
                    <div class="page-header">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                        <?php if (get_field('page_subtitle')): ?>
                            <p class="page-subtitle"><?php the_field('page_subtitle'); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="page-body">
                        <?php
                        while (have_posts()):
                            the_post();
                            ?>
                            <div class="entry-content">
                                <?php
                                the_content();

                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'bdcomic_theme'),
                                    'after' => '</div>',
                                ));
                                ?>
                            </div>
                            <?php
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>