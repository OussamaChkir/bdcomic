<?php
/**
 * Single template for Guide De Lecture custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()): the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-guide-lecture'); ?>>
                <div class="guide-header">
                    <div class="guide-hero">
                        <div class="guide-image">
                            <?php
                            $image_guide = get_field('image_guide');
                            $collection = get_field('collection_guide');
                            $collection_logo = $collection ? get_field('logo_collection', $collection->ID) : null;
                            
                            if ($image_guide): ?>
                                <img src="<?php echo esc_url($image_guide['url']); ?>" 
                                     alt="<?php echo esc_attr($image_guide['alt']); ?>" 
                                     class="guide-featured-image">
                            <?php elseif ($collection_logo): ?>
                                <img src="<?php echo esc_url($collection_logo['url']); ?>" 
                                     alt="<?php echo esc_attr($collection_logo['alt']); ?>" 
                                     class="guide-featured-image">
                            <?php elseif (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('large', array('class' => 'guide-featured-image')); ?>
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <span class="dashicons dashicons-list-view"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="guide-info">
                            <h1 class="guide-title"><?php the_title(); ?></h1>

                            <?php
                            $collection = get_field('collection_guide');
                            $description = get_field('description_guide');
                            $ordre_lecture = get_field('ordre_lecture');
                            ?>

                            <?php if ($collection): ?>
                                <div class="guide-collection">
                                    <span class="label">Collection:</span>
                                    <a href="<?php echo get_permalink($collection->ID); ?>" class="collection-link">
                                        <?php 
                                        $nom_collection = get_field('nom_collection', $collection->ID);
                                        echo $nom_collection ? esc_html($nom_collection) : esc_html($collection->post_title);
                                        ?>
                                    </a>
                                </div>
                            <?php endif; ?>


                            <?php if ($ordre_lecture && is_array($ordre_lecture)): ?>
                                <div class="guide-stats">
                                    <span class="stat-item">
                                        <strong><?php echo count($ordre_lecture); ?></strong> livre(s)
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (is_user_logged_in()) : ?>
                                <div class="guide-actions">
                                    <!-- Future: Add bookmark/favorite functionality -->
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="guide-content">
                    <div class="guide-main">
                        <?php 
                        // Use guide description if available, otherwise use collection summary
                        $collection_summary = $collection ? get_field('resume_collection', $collection->ID) : null;
                        $display_description = $description ?: $collection_summary;
                        
                        if ($display_description): ?>
                            <section class="guide-description">
                                <h2><?php echo $description ? 'Description du guide' : 'Résumé de la collection'; ?></h2>
                                <div class="description-content">
                                    <?php echo wp_kses_post($display_description); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php if (get_the_content()): ?>
                            <section class="guide-additional-content">
                                <h2>Informations complémentaires</h2>
                                <div class="additional-content">
                                    <?php the_content(); ?>
                                </div>
                            </section>
                        <?php endif; ?>


                        <?php if ($ordre_lecture && is_array($ordre_lecture)): ?>
                            <section class="guide-reading-order">
                                <h2>Ordre de lecture recommandé</h2>
                                <div class="reading-order-list">
                                    <?php foreach ($ordre_lecture as $index => $item): 
                                        $livre_id = $item['livre'];
                                        $livre = get_post($livre_id);
                                        if (!$livre) continue;
                                        
                                        $photo_devant = get_field('photo_devant', $livre->ID);
                                        $titre = get_field('titre_livre', $livre->ID);
                                        $n_sortie = get_field('n_sortie', $livre->ID);
                                        $date_sortie_livre = get_field('date_sortie_livre', $livre->ID);
                                        ?>
                                        <div class="reading-order-item" data-order="<?php echo $index + 1; ?>">
                                            <div class="order-number">
                                                <?php echo $index + 1; ?>
                                            </div>
                                            
                                            <div class="book-cover">
                                                <?php if ($photo_devant): ?>
                                                    <img src="<?php echo esc_url($photo_devant['url']); ?>"
                                                         alt="<?php echo esc_attr($photo_devant['alt']); ?>">
                                                <?php else: ?>
                                                    <div class="no-cover-placeholder">
                                                        <span class="dashicons dashicons-book"></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="book-info">
                                                <h3 class="book-title">
                                                    <a href="<?php echo get_permalink($livre->ID); ?>">
                                                        <?php echo $titre ? esc_html($titre) : esc_html($livre->post_title); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="book-meta">
                                                    <?php if ($n_sortie): ?>
                                                        <span class="book-number">N° <?php echo esc_html($n_sortie); ?></span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($date_sortie_livre): ?>
                                                        <span class="book-date"><?php echo esc_html($date_sortie_livre); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                
                                                <?php
                                                $resume_livre = get_field('resume_livre', $livre->ID);
                                                if ($resume_livre): ?>
                                                    <div class="book-summary">
                                                        <?php echo wp_trim_words($resume_livre, 15, '...'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if (is_user_logged_in()): ?>
                                                <div class="book-user-actions">
                                                    <!-- Future: Add reading status, wishlist, etc. -->
                                                    <a href="<?php echo get_permalink($livre->ID); ?>" class="btn-view-book">
                                                        Voir le livre
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="guide-sidebar">
                        <div class="guide-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($collection): ?>
                                    <li>
                                        <strong>Collection:</strong> 
                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                            <?php 
                                            $nom_collection = get_field('nom_collection', $collection->ID);
                                            echo $nom_collection ? esc_html($nom_collection) : esc_html($collection->post_title);
                                            ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($ordre_lecture && is_array($ordre_lecture)): ?>
                                    <li>
                                        <strong>Nombre de livres:</strong> <?php echo count($ordre_lecture); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <li>
                                    <strong>Publié le:</strong> <?php echo get_the_date(); ?>
                                </li>
                                
                                <li>
                                    <strong>Dernière mise à jour:</strong> <?php echo get_the_modified_date(); ?>
                                </li>
                            </ul>
                        </div>
                        
                        <?php if ($collection): ?>
                            <div class="related-guides">
                                <h3>Autres guides de cette collection</h3>
                                <?php
                                $related_guides = get_posts(array(
                                    'post_type' => 'guide_lecture',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array(get_the_ID()),
                                    'meta_query' => array(
                                        array(
                                            'key' => 'collection_guide',
                                            'value' => $collection->ID,
                                            'compare' => '='
                                        )
                                    )
                                ));
                                
                                if ($related_guides): ?>
                                    <ul class="related-guides-list">
                                        <?php foreach ($related_guides as $related_guide): ?>
                                            <li>
                                                <a href="<?php echo get_permalink($related_guide->ID); ?>">
                                                    <?php echo esc_html($related_guide->post_title); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Aucun autre guide pour cette collection.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </aside>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
