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
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/avatar.png" alt="No Image">
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

                                <?php if ($roles && is_array($roles)) : ?>
                                    <div class="artiste-roles">
                                        <?php foreach ($roles as $role) : ?>
                                            <span class="role-badge <?php echo esc_html($role); ?>"><?php echo esc_html($role); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="artiste-links">
                                    <?php if ($site_web) : ?>
                                        <a href="<?php echo esc_url($site_web); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="icon-web"></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" class="external-link" target="_blank" rel="noopener">
                                            <span class="icon-instagram"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                        Voir le profil
                                    </a>
                            </div>
                        </div>
</article>
