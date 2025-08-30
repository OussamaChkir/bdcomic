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



<?php get_footer(); ?>
