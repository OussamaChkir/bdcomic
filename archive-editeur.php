<?php
/**
 * Archive template for Editeur custom post type
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
                <h3 class="archive-search-title">Rechercher des éditeurs</h3>
                <a href="#" class="archive-clear-search">Effacer la recherche</a>
            </div>
            
            <form class="archive-search-form">
                <div class="archive-search-input-group">
                    <input type="text" 
                           class="archive-search-input" 
                           placeholder="Rechercher par nom, description..." 
                           autocomplete="off">
                </div>
                
            </form>
        </div>

        <!-- Loading Spinner -->
        <div class="archive-loading"></div>

        <!-- No Results Message -->
        <div class="archive-no-results">
            <p>Aucun éditeur trouvé avec les critères de recherche actuels.</p>
        </div>

        <!-- Results Container -->
        <div class="archive-results-container editeurs-grid">

        <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('editeur-item'); ?>>
                        <div class="editeur-content">
                            <?php
                            // Get ACF fields
                            $logo = get_field('logo_editeur');
                            $nom = get_field('nom_editeur');
                            $description = get_field('description_editeur');
                            $liens = get_field('liens');
                            ?>
                            
                            <div class="editeur-header">
                                <div class="editeur-image">
                                    <?php if ($logo) : ?>
                                        <img src="<?php echo esc_url($logo['url']); ?>" 
                                             alt="<?php echo esc_attr($logo['alt']); ?>" 
                                             class="editeur-logo">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-building"></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="editeur-title-section">
                                    <h2 class="editeur-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo $nom ? esc_html($nom) : get_the_title(); ?>
                                        </a>
                                    </h2>
                                </div>
                            </div>

                            <div class="editeur-details">
                                <?php if ($description) : ?>
                                    <div class="editeur-description">
                                        <?php echo wp_trim_words($description, 30, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($liens && is_array($liens)) : ?>
                                    <div class="editeur-links">
                                        <h4>Liens utiles:</h4>
                                        <div class="links-grid">
                                            <?php foreach ($liens as $lien) : ?>
                                                <?php if (!empty($lien['label']) && !empty($lien['url'])) : ?>
                                                    <a href="<?php echo esc_url($lien['url']); ?>" 
                                                       class="external-link" 
                                                       target="_blank" 
                                                       rel="noopener">
                                                        <span class="dashicons dashicons-external"></span>
                                                        <?php echo esc_html($lien['label']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="editeur-actions">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        Voir l'éditeur
                                    </a>
                                </div>
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
                <p><?php _e('Aucun éditeur trouvé.', 'bdcomic'); ?></p>
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
