<?php
/**
 * Single template for Livre custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-livre'); ?>>
                <header class="livre-header">
                    <div class="livre-hero">
                        <div class="livre-covers">
                            <?php
                            $photo_devant = get_field('photo_devant');
                            $photo_derriere = get_field('photo_derriere');
                            ?>
                            
                            <div class="cover-front">
                                <?php if ($photo_devant) : ?>
                                    <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                         alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                                         class="livre-cover">
                                <?php else : ?>
                                    <div class="no-cover-placeholder">
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
                        
                        <div class="livre-info">
                            <h1 class="livre-title">
                                <?php 
                                $titre = get_field('titre_livre');
                                echo $titre ? esc_html($titre) : get_the_title(); 
                                ?>
                            </h1>
                            
                            <?php
                            $variante = get_field('variante');
                            $maison_edition = get_field('maison_d\'edition');
                            $collection = get_field('collection');
                            $date_sortie = get_field('date_sortie_livre');
                            $nombre_pages = get_field('nombre_de_pages');
                            $n_sortie = get_field('n_sortie');
                            $n_frise = get_field('n_frise');
                            $tirage_limite = get_field('tirage_limite');
                            ?>
                            
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
                        </div>
                    </div>
                </header>

                <div class="livre-content">
                    <div class="livre-main">
                        <?php
                        $resume = get_field('resume_livre');
                        if ($resume) : ?>
                            <section class="livre-summary">
                                <h2>Résumé</h2>
                                <div class="summary-content">
                                    <?php echo wp_kses_post($resume); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        $equipe_creative = get_field('equipe_creative');
                        if ($equipe_creative && is_array($equipe_creative)) : ?>
                            <section class="livre-team">
                                <h2>Équipe créative</h2>
                                <div class="team-grid">
                                    <?php foreach ($equipe_creative as $membre) : ?>
                                        <?php if (!empty($membre['role']) && !empty($membre['artiste'])) : ?>
                                            <div class="team-member">
                                                <div class="member-photo">
                                                    <?php 
                                                    $photo_artiste = get_field('photo_artiste', $membre['artiste']->ID);
                                                    if ($photo_artiste) : ?>
                                                        <img src="<?php echo esc_url($photo_artiste['url']); ?>" 
                                                             alt="<?php echo esc_attr($photo_artiste['alt']); ?>">
                                                    <?php else : ?>
                                                        <div class="no-photo-placeholder">
                                                            <span class="dashicons dashicons-admin-users"></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="member-info">
                                                    <h3 class="member-name">
                                                        <a href="<?php echo get_permalink($membre['artiste']->ID); ?>">
                                                            <?php echo esc_html($membre['artiste']->post_title); ?>
                                                        </a>
                                                    </h3>
                                                    <div class="member-role"><?php echo esc_html($membre['role']); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        $liste_episodes = get_field('liste_des_episodes');
                        if ($liste_episodes && is_array($liste_episodes)) : ?>
                            <section class="livre-episodes">
                                <h2>Liste des épisodes</h2>
                                <div class="episodes-list">
                                    <?php foreach ($liste_episodes as $episode) : ?>
                                        <?php if (!empty($episode['titre_episode']) || !empty($episode['numero_episode'])) : ?>
                                            <div class="episode-item">
                                                <?php if (!empty($episode['numero_episode'])) : ?>
                                                    <div class="episode-number"><?php echo esc_html($episode['numero_episode']); ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($episode['titre_episode'])) : ?>
                                                    <div class="episode-title"><?php echo esc_html($episode['titre_episode']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        $infos_complementaires = get_field('infos_complementaires');
                        if ($infos_complementaires) : ?>
                            <section class="livre-additional">
                                <h2>Informations complémentaires</h2>
                                <div class="additional-content">
                                    <?php echo wp_kses_post($infos_complementaires); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="livre-sidebar">
                        <div class="livre-meta-details">
                            <h3>Détails techniques</h3>
                            <ul class="meta-list">
                                <?php if ($titre) : ?>
                                    <li>
                                        <strong>Titre:</strong> <?php echo esc_html($titre); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($maison_edition) : ?>
                                    <li>
                                        <strong>Éditeur:</strong> 
                                        <a href="<?php echo get_permalink($maison_edition->ID); ?>">
                                            <?php echo esc_html($maison_edition->post_title); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($collection) : ?>
                                    <li>
                                        <strong>Collection:</strong> 
                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                            <?php echo esc_html($collection->post_title); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($date_sortie) : ?>
                                    <li>
                                        <strong>Date de sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($nombre_pages) : ?>
                                    <li>
                                        <strong>Nombre de pages:</strong> <?php echo esc_html($nombre_pages); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($n_sortie) : ?>
                                    <li>
                                        <strong>N° Sortie:</strong> <?php echo esc_html($n_sortie); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($n_frise) : ?>
                                    <li>
                                        <strong>N° Frise:</strong> <?php echo esc_html($n_frise); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($tirage_limite) : ?>
                                    <li>
                                        <strong>Tirage limité:</strong> <?php echo esc_html($tirage_limite); ?> ex.
                                    </li>
                                <?php endif; ?>
                                
                                <?php
                                $isbnean13 = get_field('isbnean13');
                                if ($isbnean13) : ?>
                                    <li>
                                        <strong>ISBN/EAN13:</strong> <?php echo esc_html($isbnean13); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($equipe_creative && is_array($equipe_creative)) : ?>
                                    <li>
                                        <strong>Équipe créative:</strong> <?php echo count($equipe_creative); ?> membre(s)
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </aside>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<style>
.single-livre {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 0;
}

.livre-header {
    margin-bottom: 3rem;
}

.livre-hero {
    display: flex;
    gap: 3rem;
    align-items: flex-start;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.livre-covers {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}

.cover-front,
.cover-back {
    flex: 1;
    max-width: 200px;
}

.livre-cover {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.back-cover {
    transform: scaleX(-1);
}

.no-cover-placeholder {
    width: 100%;
    height: 300px;
    background: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.no-cover-placeholder .dashicons {
    font-size: 4rem;
    color: #ccc;
}

.livre-info {
    flex-grow: 1;
}

.livre-title {
    font-size: 2.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    line-height: 1.2;
}

.livre-variant {
    margin-bottom: 1rem;
}

.variant-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #fff3cd;
    color: #856404;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.livre-publisher,
.livre-collection {
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
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
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.95rem;
    border: 1px solid #e9ecef;
}

.meta-item strong {
    color: #666;
    margin-right: 0.5rem;
}

.livre-limited {
    margin-bottom: 1rem;
}

.limited-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #d4edda;
    color: #155724;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.livre-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.livre-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.livre-summary h2,
.livre-team h2,
.livre-episodes h2,
.livre-additional h2 {
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.livre-summary {
    margin-bottom: 3rem;
}

.summary-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
}

.livre-team {
    margin-bottom: 3rem;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.team-member {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.team-member:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.member-photo {
    flex-shrink: 0;
}

.member-photo img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.no-photo-placeholder {
    width: 60px;
    height: 60px;
    background: #f5f5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-photo-placeholder .dashicons {
    font-size: 1.5rem;
    color: #ccc;
}

.member-info {
    flex-grow: 1;
}

.member-name {
    font-size: 1rem;
    margin: 0 0 0.25rem 0;
}

.member-name a {
    color: #333;
    text-decoration: none;
}

.member-name a:hover {
    color: #007cba;
}

.member-role {
    font-size: 0.9rem;
    color: #007cba;
    font-weight: 600;
}

.livre-episodes {
    margin-bottom: 3rem;
}

.episodes-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.episode-item {
    display: flex;
    gap: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.episode-number {
    font-weight: 600;
    color: #007cba;
    min-width: 40px;
}

.episode-title {
    color: #333;
}

.livre-additional {
    margin-bottom: 2rem;
}

.additional-content {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
}

.livre-sidebar {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.livre-meta-details h3 {
    font-size: 1.3rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
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

.meta-list a {
    color: #007cba;
    text-decoration: none;
}

.meta-list a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .livre-hero {
        flex-direction: column;
        gap: 2rem;
    }
    
    .livre-covers {
        justify-content: center;
    }
    
    .cover-front,
    .cover-back {
        max-width: 150px;
    }
    
    .livre-cover,
    .no-cover-placeholder {
        height: 225px;
    }
    
    .livre-title {
        font-size: 2rem;
    }
    
    .livre-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .team-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .livre-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<?php get_footer(); ?>
