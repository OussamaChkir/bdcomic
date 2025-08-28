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

        <?php if (have_posts()) : ?>
            <div class="livres-grid">
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
            </div>

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
</main>

<style>
.livres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.livre-item {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: white;
}

.livre-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.livre-content {
    padding: 1.5rem;
}

.livre-covers {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    justify-content: center;
}

.cover-front,
.cover-back {
    flex: 1;
    max-width: 120px;
}

.livre-cover {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.back-cover {
    transform: scaleX(-1);
}

.no-image-placeholder {
    width: 100%;
    height: 180px;
    background: #f5f5f5;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e0e0;
}

.no-image-placeholder .dashicons {
    font-size: 3rem;
    color: #ccc;
}

.livre-title {
    font-size: 1.3rem;
    margin: 0 0 0.5rem 0;
    text-align: center;
}

.livre-title a {
    color: #333;
    text-decoration: none;
}

.livre-title a:hover {
    color: #007cba;
}

.livre-variant {
    text-align: center;
    margin-bottom: 0.75rem;
}

.variant-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #fff3cd;
    color: #856404;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.livre-publisher,
.livre-collection {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.livre-publisher a,
.livre-collection a {
    color: #007cba;
    text-decoration: none;
}

.livre-publisher a:hover,
.livre-collection a:hover {
    text-decoration: underline;
}

.livre-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
    color: #666;
}

.meta-item {
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.livre-limited {
    text-align: center;
    margin-bottom: 0.75rem;
}

.limited-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #d4edda;
    color: #155724;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.livre-team {
    margin-bottom: 1rem;
}

.livre-team h4 {
    font-size: 0.9rem;
    margin: 0 0 0.5rem 0;
    color: #666;
    font-weight: 600;
}

.team-list {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.team-member {
    font-size: 0.85rem;
    color: #555;
}

.team-member .role {
    font-weight: 500;
    color: #666;
}

.team-member a {
    color: #007cba;
    text-decoration: none;
}

.team-member a:hover {
    text-decoration: underline;
}

.livre-summary {
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #555;
}

.livre-isbn {
    margin-bottom: 1rem;
    text-align: center;
    color: #888;
}

.livre-actions {
    text-align: center;
}

.read-more {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background: #007cba;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: background 0.2s ease;
}

.read-more:hover {
    background: #005a87;
    color: white;
}

.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e0e0e0;
}

.page-title {
    font-size: 2rem;
    margin: 0 0 0.5rem 0;
    color: #333;
}

.archive-description {
    color: #666;
    font-size: 1.1rem;
}

.no-posts {
    text-align: center;
    padding: 3rem 0;
    color: #666;
}

@media (max-width: 768px) {
    .livres-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .livre-content {
        padding: 1rem;
    }
    
    .livre-covers {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .cover-front,
    .cover-back {
        max-width: 100px;
    }
    
    .livre-cover {
        height: 150px;
    }
    
    .no-image-placeholder {
        height: 150px;
    }
    
    .livre-meta {
        justify-content: center;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}
</style>

<?php get_footer(); ?>
