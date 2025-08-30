<article id="post-<?php the_ID(); ?>" <?php post_class('editeur-item'); ?>>
    <div class="editeur-content editeurs-grid">
        <?php
        // Get ACF fields
        $logo = get_field('logo_editeur');
        $nom = get_field('nom_editeur');
        $description = get_field('description_editeur');
        $pays = get_field('pays_editeur');
        $site_web = get_field('site_web_editeur');
        $email = get_field('email_editeur');
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

                <?php if ($pays) : ?>
                    <div class="editeur-country">
                        <span class="country-badge"><?php echo esc_html($pays); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="editeur-details">
            <?php if ($description) : ?>
                <div class="editeur-description">
                    <?php echo wp_trim_words($description, 30, '...'); ?>
                </div>
            <?php endif; ?>

            <div class="editeur-links">
                <div class="links-grid">
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        Voir l'éditeur
                    </a>
                    
                    <?php if ($site_web) : ?>
                        <a href="<?php echo esc_url($site_web); ?>" class="external-link" target="_blank" rel="noopener">
                            <span class="dashicons dashicons-admin-links"></span> Site web
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($email) : ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="external-link">
                            <span class="dashicons dashicons-email"></span> Email
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</article>
