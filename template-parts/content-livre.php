<article id="post-<?php the_ID(); ?>" <?php post_class('livre-item'); ?>>
    <div class="livre-content ">
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
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/cover.png" alt="No Image">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="livre-details">
            <h2 class="livre-title">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $titre ? esc_html($titre) : get_the_title(); ?>
                </a>
            </h2> 
            <div class="livre-actions">
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
