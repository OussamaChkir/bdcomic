<article id="post-<?php the_ID(); ?>" <?php post_class('livre-item'); ?>>
    <div class="livre-content livres-grid">
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
                        <strong>Date:</strong> <?php echo esc_html($date_sortie); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($n_sortie) : ?>
                    <span class="meta-item">
                        <strong>N°:</strong> <?php echo esc_html($n_sortie); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($nombre_pages) : ?>
                    <span class="meta-item">
                        <strong>Pages:</strong> <?php echo esc_html($nombre_pages); ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($tirage_limite) : ?>
                <div class="livre-limited">
                    <span class="limited-badge">Tirage limité</span>
                </div>
            <?php endif; ?>

            <?php if ($equipe_creative && is_array($equipe_creative)) : ?>
                <div class="livre-team">
                    <h4>Équipe créative:</h4>
                    <div class="team-list">
                        <?php foreach ($equipe_creative as $member) : ?>
                            <div class="team-member">
                                <span class="name"><?php echo esc_html($member['nom']); ?></span>
                                <?php if ($member['role']) : ?>
                                    <span class="role">(<?php echo esc_html($member['role']); ?>)</span>
                                <?php endif; ?>
                            </div>
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
                    <strong>ISBN:</strong> <?php echo esc_html($isbnean13); ?>
                </div>
            <?php endif; ?>

            <div class="livre-actions">
                <a href="<?php the_permalink(); ?>" class="read-more">
                    Voir le livre
                </a>
                
                <?php if (is_user_logged_in()) : ?>
                    <div class="book-quick-actions">
                        <?php
                        $current_user_id = get_current_user_id();
                        $post_id = get_the_ID();
                        
                        // Quick wishlist button
                        $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                        ?>
                        <button class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="wishlist"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                            <span class="dashicons <?php echo $in_wishlist ? 'dashicons-heart-filled' : 'dashicons-heart'; ?>"></span>
                        </button>
                        
                        <?php
                        // Quick read button
                        $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                        ?>
                        <button class="book-quick-action <?php echo $is_read ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="read"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                            <span class="dashicons <?php echo $is_read ? 'dashicons-yes' : 'dashicons-yes-alt'; ?>"></span>
                        </button>

                        <?php
                        // Quick missing albums button for book
                        $in_missing_albums = is_book_in_user_list($current_user_id, $post_id, 'missing_albums');
                        ?>
                        <button class="book-quick-action <?php echo $in_missing_albums ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="missing_albums"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $in_missing_albums ? __('Retirer des albums manquants', 'bdcomic_theme') : __('Ajouter aux albums manquants', 'bdcomic_theme'); ?>">
                            <span class="dashicons dashicons-minus"></span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
