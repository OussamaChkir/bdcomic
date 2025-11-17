<?php
/**
 * Single template for Livre custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-livre'); ?>>
                <div class="livre-header">
                    <div class="livre-hero">
                        <div class="livre-covers">
                            <?php
                            $photo_devant = get_field('photo_devant');
                            $photo_derriere = get_field('photo_derriere');
                            ?>

                            
                                <?php if ($photo_devant): ?>
                                <div class="cover-front zoom" onmousemove="zoom(event)" style="background-image: url('<?php echo esc_url($photo_devant['url']); ?>');">
                                    <img src="<?php echo esc_url($photo_devant['url']); ?>"
                                        alt="<?php echo esc_attr($photo_devant['alt']); ?>" class="livre-cover">
                                <?php else: ?>
                                    <div class="cover-front">
                                    <div class="no-cover-placeholder">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" alt="No Image">
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (is_user_logged_in()): ?>
                                    <?php
                                    $current_user_id = get_current_user_id();
                                    $post_id = get_the_ID();
                                    $is_loaned = is_book_in_user_list($current_user_id, $post_id, 'loaned');
                                    
                                    // Check if book has pending problem reports
                                    $problem_reports = get_field('livre_problem_reports', $post_id);
                                    $has_problem = false;
                                    if (is_array($problem_reports) && !empty($problem_reports)) {
                                        foreach ($problem_reports as $report) {
                                            if (isset($report['status']) && $report['status'] === 'pending') {
                                                $has_problem = true;
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    
                                    <div class="livre-status-icons">
                                        <?php if ($is_loaned): ?>
                                            <span class="status-icon loaned" title="<?php _e('Prêté', 'bdcomic_theme'); ?>">
                                                <span class="dashicons dashicons-share"></span>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($has_problem): ?>
                                            <span class="status-icon problem-reported" title="<?php _e('Problème signalé', 'bdcomic_theme'); ?>">
                                                <span class="dashicons dashicons-warning"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="livre-action-icons">
                                        <button class="book-action-icon report-problem-btn" 
                                                data-book-id="<?php echo $post_id; ?>"
                                                title="<?php _e('Signaler un problème', 'bdcomic_theme'); ?>"
                                                aria-label="<?php _e('Signaler un problème', 'bdcomic_theme'); ?>">
                                            <span class="dashicons dashicons-flag"></span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($photo_derriere): ?>
                                <div class="cover-back zoom" onmousemove="zoom(event)" style="background-image: url('<?php echo esc_url($photo_derriere['url']); ?>');">
                                    <img src="<?php echo esc_url($photo_derriere['url']); ?>"
                                        alt="<?php echo esc_attr($photo_derriere['alt']); ?>" class="livre-cover back-cover">
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
                            $sous_collection = get_field('sous_collection');
                            $date_sortie = get_field('date_sortie_livre');
                            $nombre_pages = get_field('nombre_de_pages');
                            $n_sortie = get_field('n_sortie');
                            $n_frise = get_field('n_frise');
                            $tirage_limite = get_field('tirage_limite');
                            ?>

                            <?php if ($variante): ?>
                                <div class="livre-variant">
                                    <span class="variant-badge">Variante</span>
                                </div>
                            <?php endif; ?>

                            <?php if ($maison_edition): ?>
                                <div class="livre-publisher">
                                    <strong>Éditeur:</strong>
                                    <a href="<?php echo get_permalink($maison_edition->ID); ?>">
                                        <?php echo esc_html($maison_edition->post_title); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($collection): ?>
                                <div class="livre-collection">
                                    <strong>Collection:</strong>
                                    <a href="<?php echo get_permalink($collection->ID); ?>">
                                        <?php echo esc_html($collection->post_title); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($sous_collection): ?>
                                <div class="livre-collection">
                                    <strong>Sous Collection:</strong>
                                    <span>
                                        <?php echo esc_html($sous_collection->post_title); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="livre-meta">
                                <?php if ($date_sortie): ?>
                                    <span class="meta-item">
                                        <strong>Sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($nombre_pages): ?>
                                    <span class="meta-item">
                                        <strong>Pages:</strong> <?php echo esc_html($nombre_pages); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($n_sortie): ?>
                                    <span class="meta-item">
                                        <strong>N° Sortie:</strong> <?php echo esc_html($n_sortie); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($n_frise): ?>
                                    <span class="meta-item">
                                        <strong>N° Frise:</strong> <?php echo esc_html($n_frise); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($tirage_limite): ?>
                                <div class="livre-limited">
                                    <span class="limited-badge">
                                        Tirage limité: <?php echo esc_html($tirage_limite); ?> ex.
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if (is_user_logged_in()): ?>
                                <div class="book-actions">
                                    <?php
                                    $current_user_id = get_current_user_id();
                                    $post_id = get_the_ID();

                                    // Owned button
									$is_owned = is_book_in_user_list($current_user_id, $post_id, 'owned');
                                    ?>
                                    <button class="book-action-btn <?php echo $is_owned ? 'active' : ''; ?>"
										data-post-id="<?php echo $post_id; ?>" data-list-type="owned"
										data-action="<?php echo $is_owned ? 'remove' : 'add'; ?>" data-post-type="livre"
										data-bs-toggle="tooltip"
										title="<?php echo $is_owned ? __('Marquer comme non possédé', 'bdcomic_theme') : __('Marquer comme possédé', 'bdcomic_theme'); ?>">
										<i class="bi <?php echo $is_owned ? 'bi-bag-check-fill' : 'bi-bag-check'; ?>"></i>
										<span
											class="btn-text"><?php echo $is_owned ? __('Marquer comme non possédé', 'bdcomic_theme') : __('Marquer comme possédé', 'bdcomic_theme'); ?></span>
									</button>
                                    
                                    <?php
                                    // Read books button
                                    $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                                    ?>
                                    <button class="book-action-btn <?php echo $is_read ? 'active' : ''; ?>"
                                        data-post-id="<?php echo $post_id; ?>" data-list-type="read"
                                        data-action="<?php echo $is_read ? 'remove' : 'add'; ?>" data-post-type="livre"
                                        data-bs-toggle="tooltip"
                                        title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                                        <i class="bi <?php echo $is_read ? 'bi-check-lg' : 'bi-check-circle-fill'; ?>"></i>
                                        <span
                                            class="btn-text"><?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?></span>
                                    </button>
									
									<?php
                                    // Wishlist button
                                    $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
									?>
									

                                    <button class="book-action-btn <?php echo $in_wishlist ? 'active' : ''; ?>"
                                        data-post-id="<?php echo $post_id; ?>" data-list-type="wishlist"
                                        data-action="<?php echo $in_wishlist ? 'remove' : 'add'; ?>" data-post-type="livre"
                                        data-bs-toggle="tooltip"
                                        title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                            <i class="bi <?php echo $in_wishlist ? 'bi-heart' : 'bi-heart-fill'; ?>"></i>
                                        <span
                                            class="btn-text"><?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?></span>
                                    </button>
                                    
                                    <?php
                                    // Loaned button
                                    $is_loaned = is_book_in_user_list($current_user_id, $post_id, 'loaned');
                                    ?>
                                    <button class="book-action-btn <?php echo $is_loaned ? 'active' : ''; ?>"
                                        data-post-id="<?php echo $post_id; ?>" data-list-type="loaned"
                                        data-action="<?php echo $is_loaned ? 'remove' : 'add'; ?>" data-post-type="livre"
                                        data-bs-toggle="tooltip"
                                        title="<?php echo $is_loaned ? __('Marquer comme non prêté', 'bdcomic_theme') : __('Marquer comme prêté', 'bdcomic_theme'); ?>">
                                        <span class="dashicons dashicons-share"></span>
                                        <span
                                            class="btn-text"><?php echo $is_loaned ? __('Marquer comme non prêté', 'bdcomic_theme') : __('Marquer comme prêté', 'bdcomic_theme'); ?></span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="livre-content">
                    <div class="livre-main">
                        <?php
                        $resume = get_field('resume_livre');
                        if ($resume): ?>
                            <section class="livre-summary">
                                <h2>Résumé</h2>
                                <div class="summary-content">
                                    <?php echo wp_kses_post($resume); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        $equipe_creative = get_field('equipe_creative');
                        if ($equipe_creative && is_array($equipe_creative)): ?>
                            <section class="livre-team">
                                <h2>Équipe créative</h2>
                                <div class="team-grid">
                                    <?php foreach ($equipe_creative as $membre): ?>
                                        <?php if (!empty($membre['role']) && !empty($membre['artiste'])): ?>
                                            <div class="team-member">
                                                <div class="member-photo">
                                                    <?php
                                                    $photo_artiste = get_field('photo_artiste', $membre['artiste']->ID);
                                                    if ($photo_artiste): ?>
                                                        <img src="<?php echo esc_url($photo_artiste['url']); ?>"
                                                            alt="<?php echo esc_attr($photo_artiste['alt']); ?>">
                                                    <?php else: ?>
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
                        if ($liste_episodes && is_array($liste_episodes)): ?>
                            <section class="livre-episodes">
                                <h2>Liste des épisodes</h2>
                                <div class="episodes-list">
                                    <?php foreach ($liste_episodes as $episode): ?>
                                        <?php if (!empty($episode['titre_episode']) || !empty($episode['numero_episode'])): ?>
                                            <div class="episode-item">
                                                <?php if (!empty($episode['numero_episode'])): ?>
                                                    <div class="episode-number"><?php echo esc_html($episode['numero_episode']); ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($episode['titre_episode'])): ?>
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
                        if ($infos_complementaires): ?>
                            <section class="livre-additional">
                                <h2>Informations complémentaires</h2>
                                <div class="additional-content">
                                    <?php echo wp_kses_post($infos_complementaires); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <?php
                        $liste_livres_variants = get_field('liste_livres_variants');
                        if ($liste_livres_variants && is_array($liste_livres_variants)): ?>
                            <section class="livre-additional">
                                <h2>Livres de base des variants</h2>
                                <div class="variant-content">
                                <?php foreach ($liste_livres_variants as $livre_var): ?>
                                    <?php 
                                    // Check if livre_variant exists and is valid
                                    if (isset($livre_var['livre_variant']) && is_object($livre_var['livre_variant']) && isset($livre_var['livre_variant']->ID)):
                                        $livre_variant_id = $livre_var['livre_variant']->ID;
                                        $livre_variant_title = $livre_var['livre_variant']->post_title;
                                        $livre_variant_permalink = get_permalink($livre_variant_id);
                                        $photo_livre_variant = get_field('photo_devant', $livre_variant_id);
                                    ?>
                                    
                                        <a href="<?php echo esc_url($livre_variant_permalink); ?>">
                                            <div class="livre-cover">
                                                <?php if ($photo_livre_variant): ?>
                                                    <img src="<?php echo esc_url($photo_livre_variant['url']); ?>" alt="<?php echo esc_attr($photo_livre_variant['alt']); ?>">
                                                <?php else: ?>
                                                    <div class="no-cover-placeholder">
                                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" alt="No Image">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="livre-title">
                                                <?php echo esc_html($livre_variant_title); ?>
                                            </div>
                                        </a>
                                    
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="livre-sidebar">
                        <div class="livre-meta-details">
                            <h3>Détails techniques</h3>
                            <ul class="meta-list">
                                <?php if ($titre): ?>
                                    <li>
                                        <strong>Titre:</strong> <?php echo esc_html($titre); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($maison_edition): ?>
                                    <li>
                                        <strong>Éditeur:</strong>
                                        <a href="<?php echo get_permalink($maison_edition->ID); ?>">
                                            <?php echo esc_html($maison_edition->post_title); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($collection): ?>
                                    <li>
                                        <strong>Collection:</strong>
                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                            <?php echo esc_html($collection->post_title); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($sous_collection): ?>  
                                    <li>
                                        <strong>Sous Collection:</strong>
                                        <span>
                                            <?php echo esc_html($sous_collection->post_title); ?>
                                        </span>
                                    </li>
                                <?php endif; ?>

                                <?php if ($date_sortie): ?>
                                    <li>
                                        <strong>Date de sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($nombre_pages): ?>
                                    <li>
                                        <strong>Nombre de pages:</strong> <?php echo esc_html($nombre_pages); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($n_sortie): ?>
                                    <li>
                                        <strong>N° Sortie:</strong> <?php echo esc_html($n_sortie); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($n_frise): ?>
                                    <li>
                                        <strong>N° Frise:</strong> <?php echo esc_html($n_frise); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($tirage_limite): ?>
                                    <li>
                                        <strong>Tirage limité:</strong> <?php echo esc_html($tirage_limite); ?> ex.
                                    </li>
                                <?php endif; ?>

                                <?php
                                $isbnean13 = get_field('isbnean13');
                                if ($isbnean13): ?>
                                    <li>
                                        <strong>ISBN:</strong> <?php echo esc_html($isbnean13); ?>
                                    </li>
                                <?php endif; ?>
                                <?php
                                $ean13 = get_field('ean13');
                                if ($ean13): ?>
                                    <li>
                                        <strong>EAN13:</strong> <?php echo esc_html($ean13); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($equipe_creative && is_array($equipe_creative)): ?>
                                    <li>
                                        <strong>Équipe créative:</strong> <?php echo count($equipe_creative); ?> membre(s)
                                    </li>
                                <?php endif; ?>

                                <h3>Liens pour acheter le livre</h3>
                                <?php $liste_des_liens = get_field('liste_des_liens'); ?>
                                <?php if ($liste_des_liens && is_array($liste_des_liens)): ?>
                                    <li>
                                        <?php foreach ($liste_des_liens as $lien): ?>
                                            <a class="btn" style="width: 100%; margin-bottom: 0.5rem;" href="<?php echo esc_url($lien['url_lien']); ?>"><?php echo esc_html($lien['titre_lien']); ?></a>
                                        <?php endforeach; ?>
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