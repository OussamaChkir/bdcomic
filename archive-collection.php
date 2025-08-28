<?php
/**
 * Archive template for Collection custom post type
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
            <div class="collections-grid">
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

                                <?php if ($date_sortie || $date_fin) : ?>
                                    <div class="collection-dates">
                                        <?php if ($date_sortie) : ?>
                                            <span class="date-sortie">
                                                <strong>Sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($date_fin) : ?>
                                            <span class="date-fin">
                                                <strong>Fin:</strong> <?php echo esc_html($date_fin); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

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
                <p><?php _e('Aucune collection trouvée.', 'bdcomic'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.collections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.collection-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.collection-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.collection-content {
    padding: 1.5rem;
}

.collection-image {
    text-align: center;
    margin-bottom: 1rem;
}

.collection-logo {
    max-width: 150px;
    height: auto;
    border-radius: 4px;
}

.no-image-placeholder {
    width: 150px;
    height: 150px;
    background: #f5f5f5;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.no-image-placeholder .dashicons {
    font-size: 3rem;
    color: #ccc;
}

.collection-title {
    font-size: 1.25rem;
    margin: 0 0 0.5rem 0;
}

.collection-title a {
    color: #333;
    text-decoration: none;
}

.collection-title a:hover {
    color: #007cba;
}

.collection-dates {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.collection-dates span {
    display: block;
    margin-bottom: 0.25rem;
}

.collection-status {
    margin-bottom: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-en-cours {
    background: #e3f2fd;
    color: #1976d2;
}

.status-terminee {
    background: #e8f5e8;
    color: #388e3c;
}

.status-abandonne {
    background: #ffebee;
    color: #d32f2f;
}

.collection-summary {
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #555;
}

.read-more {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #007cba;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
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
    .collections-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .collection-content {
        padding: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}
</style>

<?php get_footer(); ?>
