<article id="post-<?php the_ID(); ?>" <?php post_class('artiste-item'); ?>>
    <div class="artiste-content artistes-grid">
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
