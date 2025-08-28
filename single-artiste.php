<?php
/**
 * Single template for Artiste custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-artiste'); ?>>
                <header class="artiste-header">
                    <div class="artiste-hero">
                        <div class="artiste-image">
                            <?php
                            $photo = get_field('photo_artiste');
                            if ($photo) : ?>
                                <img src="<?php echo esc_url($photo['url']); ?>" 
                                     alt="<?php echo esc_attr($photo['alt']); ?>" 
                                     class="artiste-photo">
                            <?php else : ?>
                                <div class="no-image-placeholder">
                                    <span class="dashicons dashicons-admin-users"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="artiste-info">
                            <h1 class="artiste-title">
                                <?php 
                                $nom_dartiste = get_field('nom_dartiste');
                                $nom = get_field('nom_artiste');
                                $prenom = get_field('prenom_artiste');
                                
                                if ($nom_dartiste) {
                                    echo esc_html($nom_dartiste);
                                } elseif ($nom && $prenom) {
                                    echo esc_html($prenom . ' ' . $nom);
                                } else {
                                    echo get_the_title();
                                }
                                ?>
                            </h1>
                            
                            <?php if ($nom && $prenom && !$nom_dartiste) : ?>
                                <div class="artiste-real-name">
                                    <small><?php echo esc_html($prenom . ' ' . $nom); ?></small>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            $date_naissance = get_field('date_de_naissance_artiste');
                            $deces = get_field('deces');
                            $nationalite = get_field('nationalite_artiste');
                            $roles = get_field('roles_artiste');
                            ?>
                            
                            <?php if ($date_naissance || $deces) : ?>
                                <div class="artiste-dates">
                                    <?php if ($date_naissance) : ?>
                                        <div class="date-item">
                                            <span class="date-label">Né(e):</span>
                                            <span class="date-value"><?php echo esc_html($date_naissance); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($deces) : ?>
                                        <div class="date-item">
                                            <span class="date-label">Décédé(e):</span>
                                            <span class="date-value"><?php echo esc_html($deces); ?></span>
                                        </div>
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
                        </div>
                    </div>
                </header>

                <div class="artiste-content">
                    <div class="artiste-main">
                        <?php
                        $biographie = get_field('biographie_artiste');
                        if ($biographie) : ?>
                            <section class="artiste-bio">
                                <h2>Biographie</h2>
                                <div class="bio-content">
                                    <?php echo wp_kses_post($biographie); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        // Get books by this artist
                        $books_by_artist = get_posts(array(
                            'post_type' => 'livre',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'equipe_creative',
                                    'value' => '"artiste";s:' . strlen(get_the_ID()) . ':"' . get_the_ID() . '"',
                                    'compare' => 'LIKE'
                                )
                            )
                        ));
                        
                        if ($books_by_artist) : ?>
                            <section class="artiste-works">
                                <h2>Œuvres</h2>
                                <div class="works-grid">
                                    <?php foreach ($books_by_artist as $book) : 
                                        $photo_devant = get_field('photo_devant', $book->ID);
                                        $titre = get_field('titre_livre', $book->ID);
                                        $equipe_creative = get_field('equipe_creative', $book->ID);
                                        $maison_edition = get_field('maison_d\'edition', $book->ID);
                                        $date_sortie = get_field('date_sortie_livre', $book->ID);
                                        
                                        // Find the artist's role in this book
                                        $artist_role = '';
                                        if ($equipe_creative && is_array($equipe_creative)) {
                                            foreach ($equipe_creative as $membre) {
                                                if ($membre['artiste'] && $membre['artiste']->ID == get_the_ID()) {
                                                    $artist_role = $membre['role'];
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                        <div class="work-item">
                                            <div class="work-cover">
                                                <?php if ($photo_devant) : ?>
                                                    <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                                         alt="<?php echo esc_attr($photo_devant['alt']); ?>">
                                                <?php else : ?>
                                                    <div class="no-cover-placeholder">
                                                        <span class="dashicons dashicons-book"></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="work-info">
                                                <h3 class="work-title">
                                                    <a href="<?php echo get_permalink($book->ID); ?>">
                                                        <?php echo $titre ? esc_html($titre) : esc_html($book->post_title); ?>
                                                    </a>
                                                </h3>
                                                <?php if ($artist_role) : ?>
                                                    <div class="work-role"><?php echo esc_html($artist_role); ?></div>
                                                <?php endif; ?>
                                                <?php if ($maison_edition) : ?>
                                                    <div class="work-publisher"><?php echo esc_html($maison_edition->post_title); ?></div>
                                                <?php endif; ?>
                                                <?php if ($date_sortie) : ?>
                                                    <div class="work-date"><?php echo esc_html($date_sortie); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="artiste-sidebar">
                        <div class="artiste-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($nom && $prenom) : ?>
                                    <li>
                                        <strong>Nom réel:</strong> <?php echo esc_html($prenom . ' ' . $nom); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($nom_dartiste) : ?>
                                    <li>
                                        <strong>Nom d'artiste:</strong> <?php echo esc_html($nom_dartiste); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($date_naissance) : ?>
                                    <li>
                                        <strong>Né(e):</strong> <?php echo esc_html($date_naissance); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($deces) : ?>
                                    <li>
                                        <strong>Décédé(e):</strong> <?php echo esc_html($deces); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($nationalite) : ?>
                                    <li>
                                        <strong>Nationalité:</strong> <?php echo esc_html($nationalite); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($roles && is_array($roles)) : ?>
                                    <li>
                                        <strong>Rôles:</strong> <?php echo esc_html(implode(', ', $roles)); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($books_by_artist) : ?>
                                    <li>
                                        <strong>Œuvres:</strong> <?php echo count($books_by_artist); ?> livre(s)
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php
                        $site_web = get_field('site_web');
                        $instagram = get_field('instagram');
                        if ($site_web || $instagram) : ?>
                            <div class="artiste-links">
                                <h3>Liens</h3>
                                <div class="links-list">
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
                        <?php endif; ?>
                    </aside>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<style>
.single-artiste {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 0;
}

.artiste-header {
    margin-bottom: 3rem;
}

.artiste-hero {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.artiste-image {
    flex-shrink: 0;
}

.artiste-photo {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f0f0f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.no-image-placeholder {
    width: 250px;
    height: 250px;
    background: #f5f5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #f0f0f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.no-image-placeholder .dashicons {
    font-size: 5rem;
    color: #ccc;
}

.artiste-info {
    flex-grow: 1;
}

.artiste-title {
    font-size: 2.5rem;
    margin: 0 0 0.5rem 0;
    color: #333;
    line-height: 1.2;
}

.artiste-real-name {
    font-size: 1.2rem;
    color: #666;
    font-style: italic;
    margin-bottom: 1rem;
}

.artiste-dates {
    margin-bottom: 1rem;
}

.date-item {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.date-label {
    font-weight: 600;
    color: #666;
    margin-right: 0.5rem;
}

.date-value {
    color: #333;
}

.artiste-nationality {
    margin-bottom: 1rem;
}

.nationality-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #e8f4fd;
    color: #1976d2;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.artiste-roles {
    margin-bottom: 1rem;
}

.role-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    background: #f0f0f0;
    color: #555;
    border-radius: 15px;
    font-size: 0.85rem;
    margin: 0.2rem;
    font-weight: 500;
}

.artiste-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.artiste-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.artiste-bio h2,
.artiste-works h2 {
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.artiste-bio {
    margin-bottom: 3rem;
}

.bio-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
}

.artiste-works {
    margin-top: 2rem;
}

.works-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.work-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.work-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.work-cover {
    height: 250px;
    overflow: hidden;
}

.work-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-cover-placeholder {
    width: 100%;
    height: 100%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-cover-placeholder .dashicons {
    font-size: 3rem;
    color: #ccc;
}

.work-info {
    padding: 1rem;
}

.work-title {
    font-size: 1rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}

.work-title a {
    color: #333;
    text-decoration: none;
}

.work-title a:hover {
    color: #007cba;
}

.work-role {
    font-size: 0.9rem;
    color: #007cba;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.work-publisher,
.work-date {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.artiste-sidebar {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.artiste-meta h3,
.artiste-links h3 {
    font-size: 1.3rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.artiste-links {
    margin-top: 2rem;
}

.meta-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.meta-list li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 1rem;
}

.meta-list li:last-child {
    border-bottom: none;
}

.meta-list strong {
    color: #666;
    margin-right: 0.5rem;
}

.links-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.external-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.external-link:hover {
    background: #e9ecef;
    color: #333;
    border-color: #dee2e6;
}

.external-link .dashicons {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .artiste-hero {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .artiste-photo,
    .no-image-placeholder {
        width: 200px;
        height: 200px;
    }
    
    .artiste-title {
        font-size: 2rem;
    }
    
    .artiste-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .works-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .work-cover {
        height: 200px;
    }
}
</style>

<?php get_footer(); ?>
