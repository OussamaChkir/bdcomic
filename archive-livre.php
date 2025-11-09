<?php
/**
 * Archive template for Livre custom post type
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
                    <a href="<?php the_permalink(); ?>">
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
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" alt="No Image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="livre-details">
                                <h2 class="livre-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                                    </a>
                                </h2>
                            </div>
                            <div class="livre-actions">
                                    <?php if (is_user_logged_in()) : ?>
                                    <div class="book-quick-actions">
                                        <?php
                                        $current_user_id = get_current_user_id();
                                        $post_id = get_the_ID();
                                        
                                        // Quick wishlist button
                                        $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                                        ?>
                                        <button class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>" 
                                                data-post-id="<?php echo $post_id; ?>" 
                                                data-list-type="wishlist"
                                                data-post-type="livre"
                                                data-bs-toggle="tooltip" 
                                                title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                            <i class="bi <?php echo $in_wishlist ? 'bi-heart-fill' : 'bi-heart'; ?>"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                                </a>
                    </article>
                <?php endwhile; ?>

        <?php else : ?>
            <div class="no-posts">
                <p><?php _e('Aucun livre trouvé.', 'bdcomic'); ?></p>
            </div>
        <?php endif; ?>
        </div>

        <!-- Pagination Container -->
            <?php
            // Initial pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
            ?>
    </div>
</main>



<?php get_footer(); ?>
