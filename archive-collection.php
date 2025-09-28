<?php
/**
 * Archive template for Collection custom post type
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
                <h3 class="archive-search-title">Rechercher des collections</h3>
                <a href="#" class="archive-clear-search">Effacer la recherche</a>
            </div>
            
            <form class="archive-search-form">
                <div class="archive-search-input-group">
                    <input type="text" 
                           class="archive-search-input" 
                           placeholder="Rechercher par titre, résumé..." 
                           autocomplete="off">
                </div>
                
                <div class="archive-filters-row">
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Statut</label>
                        <select class="archive-filter-select" data-filter="status">
                            <option value="">Tous les statuts</option>
                            <option value="En cours">En cours</option>
                            <option value="Terminée">Terminée</option>
                            <option value="Abandonné">Abandonné</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div class="archive-loading"></div>

        <!-- No Results Message -->
        <div class="archive-no-results">
            <p>Aucune collection trouvée avec les critères de recherche actuels.</p>
        </div>

        <!-- Results Container -->
        <div class="archive-results-container collections-grid">

        <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('collection-item'); ?>>
                        <div class="collection-content">
                            <?php
                            // Get ACF fields
                            $logo = get_field('logo_collection');
                            $nom = get_field('nom_collection');
                            $date_sortie = get_field('date_de_sortie_collection');
                            $date_fin = get_field('date_de_fin_collection');
                            $etat = get_field('etat_collection');
                            $resume = get_field('resume_collection');
                            ?>
                            
                            <div class="collection-image">
                                <?php if ($logo) : ?>
                                    <img src="<?php echo esc_url($logo['url']); ?>" 
                                         alt="<?php echo esc_attr($logo['alt']); ?>" 
                                         class="collection-logo">
                                <?php else : ?>
                                    <div class="no-image-placeholder">
                                        <span class="dashicons dashicons-book-alt"></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="collection-details">
                                <h2 class="collection-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $nom ? esc_html($nom) : get_the_title(); ?>
                                    </a>
                                </h2>

                            
                                <?php if ($etat) : ?>
                                    <div class="collection-status">
                                        <span class="status-badge status-<?php echo esc_attr(strtolower($etat)); ?>">
                                            <?php echo esc_html($etat); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($resume) : ?>
                                    <div class="collection-summary">
                                        <?php echo wp_trim_words($resume, 20, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Voir la collection
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
                <p><?php _e('Aucune collection trouvée.', 'bdcomic'); ?></p>
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
