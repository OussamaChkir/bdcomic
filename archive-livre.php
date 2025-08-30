<?php
/**
 * Archive template for Livre custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
            <?php
            $archive_description = get_the_archive_description();
            if ($archive_description) {
                echo '<div class="archive-description">' . $archive_description . '</div>';
            }
            ?>
        </header>

        <!-- Archive Search Container -->
        <div class="archive-search-container">
            <div class="archive-search-header">
                <h3 class="archive-search-title">Rechercher des livres</h3>
                <a href="#" class="archive-clear-search">Effacer la recherche</a>
            </div>
            
            <form class="archive-search-form">
                <div class="archive-search-input-group">
                    <input type="text" 
                           class="archive-search-input" 
                           placeholder="Rechercher par titre, résumé, équipe créative..." 
                           autocomplete="off">
                </div>
                
                <div class="archive-filters-row">
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Éditeur</label>
                        <select class="archive-filter-select" data-filter="publisher">
                            <option value="">Tous les éditeurs</option>
                            <?php
                            // Get unique publishers from ACF field
                            $publishers = array();
                            $livres = get_posts(array(
                                'post_type' => 'livre',
                                'posts_per_page' => -1,
                                'meta_key' => 'maison_d\'edition'
                            ));
                            
                            foreach ($livres as $livre) {
                                $publisher = get_field('maison_d\'edition', $livre->ID);
                                if ($publisher && !in_array($publisher->post_title, $publishers)) {
                                    $publishers[] = $publisher->post_title;
                                }
                            }
                            
                            sort($publishers);
                            foreach ($publishers as $publisher) {
                                echo '<option value="' . esc_attr($publisher) . '">' . esc_html($publisher) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Collection</label>
                        <select class="archive-filter-select" data-filter="collection">
                            <option value="">Toutes les collections</option>
                            <?php
                            // Get unique collections from ACF field
                            $collections = array();
                            $livres = get_posts(array(
                                'post_type' => 'livre',
                                'posts_per_page' => -1,
                                'meta_key' => 'collection'
                            ));
                            
                            foreach ($livres as $livre) {
                                $collection = get_field('collection', $livre->ID);
                                if ($collection && !in_array($collection->post_title, $collections)) {
                                    $collections[] = $collection->post_title;
                                }
                            }
                            
                            sort($collections);
                            foreach ($collections as $collection) {
                                echo '<option value="' . esc_attr($collection) . '">' . esc_html($collection) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Type</label>
                        <select class="archive-filter-select" data-filter="variant">
                            <option value="">Tous les livres</option>
                            <option value="1">Variantes uniquement</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div class="archive-loading"></div>

        <!-- No Results Message -->
        <div class="archive-no-results">
            <p>Aucun livre trouvé avec les critères de recherche actuels.</p>
        </div>

        <!-- Results Container -->
        <div class="archive-results-container livres-grid">

        <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('livre-item'); ?>>
                        <div class="livre-content">
                            <?php
                            // Get ACF fields
                            $photo_devant = get_field('photo_devant');
                            $photo_derriere = get_field('photo_derriere');
                            $titre = get_field('titre_livre');
                            $variante = get_field('variante');
                            $maison_edition = get_field('maison_d\'edition');
                            $collection = get_field('collection');
                            $tirage_limite = get_field('tirage_limite');
                            $n_sortie = get_field('n_sortie');
                            $n_frise = get_field('n_frise');
                            $date_sortie = get_field('date_sortie_livre');
                            $nombre_pages = get_field('nombre_de_pages');
                            $resume = get_field('resume_livre');
                            $equipe_creative = get_field('equipe_creative');
                            $isbnean13 = get_field('isbnean13');
                            ?>
                            
                            <div class="livre-covers">
                                <div class="cover-front">
                                    <?php if ($photo_devant) : ?>
                                        <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                             alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                             class="livre-cover">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-book"></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($photo_derriere) : ?>
                                    <div class="cover-back">
                                        <img src="<?php echo esc_url($photo_derriere['url']); ?>" 
                                             alt="<?php echo esc_attr($photo_derriere['alt']); ?>" 
                                             class="livre-cover back-cover">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="livre-details">
                                <h2 class="livre-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                                    </a>
                                </h2>

                                <?php if ($variante) : ?>
                                    <div class="livre-variant">
                                        <span class="variant-badge">Variante</span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($maison_edition) : ?>
                                    <div class="livre-publisher">
                                        <strong>Éditeur:</strong> 
                                        <a href="<?php echo get_permalink($maison_edition->ID); ?>">
                                            <?php echo esc_html($maison_edition->post_title); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($collection) : ?>
                                    <div class="livre-collection">
                                        <strong>Collection:</strong> 
                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                            <?php echo esc_html($collection->post_title); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="livre-meta">
                                    <?php if ($date_sortie) : ?>
                                        <span class="meta-item">
                                            <strong>Sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($nombre_pages) : ?>
                                        <span class="meta-item">
                                            <strong>Pages:</strong> <?php echo esc_html($nombre_pages); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($n_sortie) : ?>
                                        <span class="meta-item">
                                            <strong>N° Sortie:</strong> <?php echo esc_html($n_sortie); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($n_frise) : ?>
                                        <span class="meta-item">
                                            <strong>N° Frise:</strong> <?php echo esc_html($n_frise); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($tirage_limite) : ?>
                                    <div class="livre-limited">
                                        <span class="limited-badge">
                                            Tirage limité: <?php echo esc_html($tirage_limite); ?> ex.
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($equipe_creative && is_array($equipe_creative)) : ?>
                                    <div class="livre-team">
                                        <h4>Équipe créative:</h4>
                                        <div class="team-list">
                                            <?php foreach ($equipe_creative as $membre) : ?>
                                                <?php if (!empty($membre['role']) && !empty($membre['artiste'])) : ?>
                                                    <div class="team-member">
                                                        <span class="role"><?php echo esc_html($membre['role']); ?>:</span>
                                                        <a href="<?php echo get_permalink($membre['artiste']->ID); ?>">
                                                            <?php echo esc_html($membre['artiste']->post_title); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($resume) : ?>
                                    <div class="livre-summary">
                                        <?php echo wp_trim_words($resume, 25, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isbnean13) : ?>
                                    <div class="livre-isbn">
                                        <small><strong>ISBN/EAN13:</strong> <?php echo esc_html($isbnean13); ?></small>
                                    </div>
                                <?php endif; ?>

                                <div class="livre-actions">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        Voir le livre
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
                <p><?php _e('Aucun livre trouvé.', 'bdcomic'); ?></p>
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
