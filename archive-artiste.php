<?php
/**
 * Archive template for Artiste custom post type
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
            <div class="artistes-grid">
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
                                        <span class="dashicons dashicons-admin-users"></span>
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

                                <?php if ($date_naissance || $deces) : ?>
                                    <div class="artiste-dates">
                                        <?php if ($date_naissance) : ?>
                                            <span class="date-naissance">
                                                <strong>Né(e):</strong> <?php echo esc_html($date_naissance); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($deces) : ?>
                                            <span class="date-deces">
                                                <strong>Décédé(e):</strong> <?php echo esc_html($deces); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($nationalite) : ?>
                                    <div class="artiste-nationality">
                                        <span class="nationality-badge">
                                            <?php echo esc_html($nationalite); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($roles && is_array($roles)) : ?>
                                    <div class="artiste-roles">
                                        <?php foreach ($roles as $role) : ?>
                                            <span class="role-badge"><?php echo esc_html($role); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($biographie) : ?>
                                    <div class="artiste-bio">
                                        <?php echo wp_trim_words($biographie, 25, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="artiste-links">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        Voir le profil
                                    </a>
                                    
                                    <?php if ($site_web) : ?>
                                        <a href="<?php echo esc_url($site_web); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="dashicons dashicons-admin-links"></span> Site web
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="dashicons dashicons-instagram"></span> Instagram
                                        </a>
                                    <?php endif; ?>
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
                <p><?php _e('Aucun artiste trouvé.', 'bdcomic'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.artistes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.artiste-item {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: white;
}

.artiste-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.artiste-content {
    padding: 1.5rem;
}

.artiste-image {
    text-align: center;
    margin-bottom: 1.5rem;
}

.artiste-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f0f0f0;
}

.no-image-placeholder {
    width: 120px;
    height: 120px;
    background: #f5f5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid #f0f0f0;
}

.no-image-placeholder .dashicons {
    font-size: 2.5rem;
    color: #ccc;
}

.artiste-title {
    font-size: 1.3rem;
    margin: 0 0 0.25rem 0;
    text-align: center;
}

.artiste-title a {
    color: #333;
    text-decoration: none;
}

.artiste-title a:hover {
    color: #007cba;
}

.artiste-real-name {
    text-align: center;
    margin-bottom: 0.5rem;
    color: #666;
    font-style: italic;
}

.artiste-dates {
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
    color: #666;
    text-align: center;
}

.artiste-dates span {
    display: block;
    margin-bottom: 0.25rem;
}

.artiste-nationality {
    text-align: center;
    margin-bottom: 0.75rem;
}

.nationality-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #e8f4fd;
    color: #1976d2;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.artiste-roles {
    text-align: center;
    margin-bottom: 1rem;
}

.role-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    background: #f0f0f0;
    color: #555;
    border-radius: 10px;
    font-size: 0.75rem;
    margin: 0.1rem;
}

.artiste-bio {
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #555;
    text-align: center;
}

.artiste-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
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

.external-link {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.4rem 0.8rem;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.8rem;
    transition: background 0.2s ease;
}

.external-link:hover {
    background: #e9ecef;
    color: #333;
}

.external-link .dashicons {
    font-size: 0.9rem;
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
    .artistes-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .artiste-content {
        padding: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .artiste-links {
        flex-direction: column;
        align-items: stretch;
    }
    
    .read-more,
    .external-link {
        text-align: center;
    }
}
</style>

<?php get_footer(); ?>
