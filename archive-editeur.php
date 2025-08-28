<?php
/**
 * Archive template for Editeur custom post type
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
            <div class="editeurs-grid">
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
                <p><?php _e('Aucun éditeur trouvé.', 'bdcomic'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.editeurs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.editeur-item {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: white;
}

.editeur-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.editeur-content {
    padding: 1.5rem;
}

.editeur-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.editeur-image {
    flex-shrink: 0;
}

.editeur-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
    background: white;
}

.no-image-placeholder {
    width: 80px;
    height: 80px;
    background: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #f0f0f0;
}

.no-image-placeholder .dashicons {
    font-size: 2rem;
    color: #ccc;
}

.editeur-title-section {
    flex-grow: 1;
}

.editeur-title {
    font-size: 1.4rem;
    margin: 0;
    line-height: 1.3;
}

.editeur-title a {
    color: #333;
    text-decoration: none;
}

.editeur-title a:hover {
    color: #007cba;
}

.editeur-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.editeur-description {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #555;
}

.editeur-links h4 {
    font-size: 0.9rem;
    margin: 0 0 0.5rem 0;
    color: #666;
    font-weight: 600;
}

.links-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.external-link {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.4rem 0.8rem;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.external-link:hover {
    background: #e9ecef;
    color: #333;
    border-color: #dee2e6;
}

.external-link .dashicons {
    font-size: 0.8rem;
}

.editeur-actions {
    margin-top: 0.5rem;
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
    .editeurs-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .editeur-content {
        padding: 1rem;
    }
    
    .editeur-header {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .editeur-title {
        font-size: 1.2rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .links-grid {
        justify-content: center;
    }
}
</style>

<?php get_footer(); ?>
