<?php
/**
 * Archive template for Guide De Lecture custom post type
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
                <h3 class="archive-search-title">Rechercher des guides de lecture</h3>
                <a href="#" class="archive-clear-search">Effacer la recherche</a>
            </div>
            
            <form class="archive-search-form">
                <div class="archive-search-input-group">
                    <input type="text" 
                           class="archive-search-input" 
                           placeholder="Rechercher par titre, description..." 
                           autocomplete="off">
                </div>
                
                <div class="archive-filters-row">
                    <div class="archive-filter-group">
                        <label class="archive-filter-label">Collection</label>
                        <select class="archive-filter-select" data-filter="collection">
                            <option value="">Toutes les collections</option>
                            <?php
                            // Get all collections for filter
                            $collections = get_posts(array(
                                'post_type' => 'collection',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC'
                            ));
                            foreach ($collections as $collection) {
                                $nom_collection = get_field('nom_collection', $collection->ID);
                                echo '<option value="' . $collection->ID . '">' . 
                                     ($nom_collection ? esc_html($nom_collection) : esc_html($collection->post_title)) . 
                                     '</option>';
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
            <p>Aucun guide de lecture trouvé avec les critères de recherche actuels.</p>
        </div>

        <!-- Results Container -->
        <div class="archive-results-container guides-grid">

        <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('guide-item'); ?>>
                        <div class="guide-content">
                            <?php
                            // Get ACF fields
                            $image_guide = get_field('image_guide');
                            $description = get_field('description_guide');
                            $collection = get_field('collection_guide');
                            $ordre_lecture = get_field('ordre_lecture');
                            ?>
                            
                            <div class="guide-image">
                                <?php 
                                $collection_logo = $collection ? get_field('logo_collection', $collection->ID) : null;
                                
                                if ($image_guide) : ?>
                                    <img src="<?php echo esc_url($image_guide['url']); ?>" 
                                         alt="<?php echo esc_attr($image_guide['alt']); ?>" 
                                         class="guide-thumbnail">
                                <?php elseif ($collection_logo) : ?>
                                    <img src="<?php echo esc_url($collection_logo['url']); ?>" 
                                         alt="<?php echo esc_attr($collection_logo['alt']); ?>" 
                                         class="guide-thumbnail">
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'guide-thumbnail')); ?>
                                <?php else : ?>
                                    <div class="no-image-placeholder">
                                        <span class="dashicons dashicons-list-view"></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="guide-details">
                                <h2 class="guide-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <?php if ($collection) : ?>
                                    <div class="guide-collection">
                                        <strong>Collection:</strong> 
                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                            <?php 
                                            $nom_collection = get_field('nom_collection', $collection->ID);
                                            echo $nom_collection ? esc_html($nom_collection) : esc_html($collection->post_title); 
                                            ?>
                                        </a>
                                    </div>
                                <?php endif; ?>


                                <?php 
                                // Use guide description if available, otherwise use collection summary
                                $collection_summary = $collection ? get_field('resume_collection', $collection->ID) : null;
                                $display_description = $description ?: $collection_summary;
                                
                                if ($display_description) : ?>
                                    <div class="guide-description">
                                        <?php echo wp_trim_words($display_description, 25, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($ordre_lecture && is_array($ordre_lecture)) : ?>
                                    <div class="guide-books-count">
                                        <strong><?php echo count($ordre_lecture); ?></strong> livre(s) dans ce guide
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Voir le guide
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>

        <?php else : ?>
            <div class="no-posts">
                <h2>Aucun guide de lecture trouvé</h2>
                <p>Il n'y a actuellement aucun guide de lecture publié.</p>
                <?php if (current_user_can('edit_posts')) : ?>
                    <p><a href="<?php echo admin_url('post-new.php?post_type=guide_lecture'); ?>" class="button">Créer le premier guide</a></p>
                <?php endif; ?>
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
