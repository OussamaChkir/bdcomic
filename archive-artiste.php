<?php
/**
 * Archive template for Artiste custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
            <?php
            $archive_description = get_the_archive_description();
            if ($archive_description) {
                echo '<div class="archive-description">' . $archive_description . '</div>';
            }
            ?>
        </div>

        <!-- Archive Search Container -->
        <div class="archive-search-container">
            <div class="archive-search-header">
                <h3 class="archive-search-title">Rechercher des artistes</h3>
                <a href="#" class="archive-clear-search">Effacer la recherche</a>
            </div>
            
            <form class="archive-search-form">
                <div class="archive-search-input-group">
                    <input type="text" 
                           class="archive-search-input" 
                           placeholder="Rechercher par nom d'artiste, nom d'artiste, biographie..." 
                           autocomplete="off">
                </div>
                
                <div class="archive-filters-row">
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Rôle</label>
                        <select class="archive-filter-select" data-filter="role">
                            <option value="">Tous les rôles</option>
                            <?php
                            // Get unique roles from ACF field
                            $roles = array();
                            $artistes = get_posts(array(
                                'post_type' => 'artiste',
                                'posts_per_page' => -1,
                                'meta_key' => 'roles_artiste'
                            ));
                            
                            foreach ($artistes as $artiste) {
                                $artist_roles = get_field('roles_artiste', $artiste->ID);
                                if ($artist_roles && is_array($artist_roles)) {
                                    foreach ($artist_roles as $role) {
                                        if (!in_array($role, $roles)) {
                                            $roles[] = $role;
                                        }
                                    }
                                }
                            }
                            
                            sort($roles);
                            foreach ($roles as $role) {
                                echo '<option value="' . esc_attr($role) . '">' . esc_html($role) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div class="archive-loading"></div>

        <!-- No Results Message -->
        <div class="archive-no-results">
            <p>Aucun artiste trouvé avec les critères de recherche actuels.</p>
        </div>

        <!-- Results Container -->
        <div class="archive-results-container artistes-grid">

        <?php if (have_posts()) : ?>
           
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('artiste-item'); ?>>
                        <div class="artiste-content">
                            <?php
                            // Get ACF fields
                            $photo = get_field('photo_artiste');
                            $nom = get_field('nom_artiste');
                            $prenom = get_field('prenom_artiste');
                            $nom_dartiste = get_field('nom_dartiste');
                            $date_naissance = get_field('date_de_naissance_artiste');
                            $deces = get_field('deces');
                            $nationalite = get_field('nationalite_artiste');
                            $roles = get_field('roles_artiste');
                            $biographie = get_field('biographie_artiste');
                            $site_web = get_field('site_web');
                            $instagram = get_field('instagram');
                            ?>
                            
                            <div class="artiste-image">
                                <?php if ($photo) : ?>
                                    <img src="<?php echo esc_url($photo['url']); ?>" 
                                         alt="<?php echo esc_attr($photo['alt']); ?>" 
                                         class="artiste-photo">
                                <?php else : ?>
                                    <div class="no-image-placeholder">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/avatar.png" alt="No Image">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="artiste-details">
                                <h2 class="artiste-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        if ($nom_dartiste) {
                                            echo esc_html($nom_dartiste);
                                        } elseif ($nom && $prenom) {
                                            echo esc_html($prenom . ' ' . $nom);
                                        } else {
                                            echo get_the_title();
                                        }
                                        ?>
                                    </a>
                                </h2>

                                <?php if ($nom && $prenom && !$nom_dartiste) : ?>
                                    <div class="artiste-real-name">
                                        <small><?php echo esc_html($prenom . ' ' . $nom); ?></small>
                                    </div>
                                <?php endif; ?>

                                <?php if ($roles && is_array($roles)) : ?>
                                    <div class="artiste-roles">
                                        <?php foreach ($roles as $role) : ?>
                                            <span class="role-badge <?php echo esc_html($role); ?>"><?php echo esc_html($role); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="artiste-links">
                                    <?php if ($site_web) : ?>
                                        <a href="<?php echo esc_url($site_web); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="icon-web"></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="icon-instagram"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                        Voir le profil
                                    </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
            ?>

        <?php else : ?>
            <div class="no-posts">
                <p><?php _e('Aucun artiste trouvé.', 'bdcomic'); ?></p>
            </div>
        <?php endif; ?>
        </div>

        <!-- Pagination Container -->
        <div class="archive-pagination">
            <?php
            // Initial pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
            ?>
        </div>
    </div>
</main>



<?php get_footer(); ?>
