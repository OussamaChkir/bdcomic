<article id="post-<?php the_ID(); ?>" <?php post_class('collection-item'); ?>>
    <div class="collection-content collections-grid">
        <?php
        // Get ACF fields
        $logo = get_field('logo_collection');
        $titre = get_field('titre_collection');
        $date_debut = get_field('date_debut_collection');
        $date_fin = get_field('date_fin_collection');
        $statut = get_field('statut_collection');
        $resume = get_field('resume_collection');
        $editeur = get_field('editeur_collection');
        ?>
        
        <div class="collection-image">
            <?php if ($logo) : ?>
                <img src="<?php echo esc_url($logo['url']); ?>" 
                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                     class="collection-logo">
            <?php else : ?>
                <div class="no-image-placeholder">
                    <span class="dashicons dashicons-book"></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="collection-details">
            <h2 class="collection-title">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                </a>
            </h2>

            <?php if ($date_debut || $date_fin) : ?>
                <div class="collection-dates">
                    <?php if ($date_debut) : ?>
                        <span class="date-debut">
                            <strong>Début:</strong> <?php echo esc_html($date_debut); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($date_fin) : ?>
                        <span class="date-fin">
                            <strong>Fin:</strong> <?php echo esc_html($date_fin); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($statut) : ?>
                <div class="collection-status">
                    <span class="status-badge status-<?php echo esc_attr(strtolower($statut)); ?>">
                        <?php echo esc_html($statut); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($editeur) : ?>
                <div class="collection-publisher">
                    <strong>Éditeur:</strong> 
                    <a href="<?php echo get_permalink($editeur->ID); ?>">
                        <?php echo esc_html($editeur->post_title); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($resume) : ?>
                <div class="collection-summary">
                    <?php echo wp_trim_words($resume, 25, '...'); ?>
                </div>
            <?php endif; ?>

            <div class="collection-links">
                <a href="<?php the_permalink(); ?>" class="read-more">
                    Voir la collection
                </a>
                
                <?php if (is_user_logged_in()) : ?>
                    <div class="book-quick-actions">
                        <?php
                        $current_user_id = get_current_user_id();
                        $post_id = get_the_ID();
                        
                        // Quick collection wishlist button
                        $in_collection_wishlist = is_book_in_user_list($current_user_id, $post_id, 'collection_wishlist');
                        ?>
                        <button class="book-quick-action <?php echo $in_collection_wishlist ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="collection_wishlist"
                                data-post-type="collection"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $in_collection_wishlist ? __('Retirer des souhaits de collection', 'bdcomic_theme') : __('Ajouter aux souhaits de collection', 'bdcomic_theme'); ?>">
                            <span class="dashicons dashicons-star-filled"></span>
                        </button>
                        
                        <?php
                        // Quick missing albums button
                        $in_missing_albums = is_book_in_user_list($current_user_id, $post_id, 'missing_albums');
                        ?>
                        <button class="book-quick-action <?php echo $in_missing_albums ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="missing_albums"
                                data-post-type="collection"
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
