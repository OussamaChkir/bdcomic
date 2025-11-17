<?php
/**
 * Single template for Artiste custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-artiste'); ?>>
                <div class="artiste-header">
                    <div class="artiste-hero">
                        <div class="artiste-image">
                            <?php
                            $photo = get_field('photo_artiste');
                            if ($photo): ?>
                                <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>"
                                    class="artiste-photo">
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder/avatar.png" alt="No Image">
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

                            <?php if ($nom && $prenom && !$nom_dartiste): ?>
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

                            <?php if ($date_naissance || $deces): ?>
                                <div class="artiste-dates">
                                    <?php if ($date_naissance): ?>
                                        <div class="date-item">
                                            <span class="date-label">Né(e):</span>
                                            <span class="date-value"><?php echo esc_html($date_naissance); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($deces): ?>
                                        <div class="date-item">
                                            <span class="date-label">Décédé(e):</span>
                                            <span class="date-value"><?php echo esc_html($deces); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($nationalite): ?>
                                <div class="artiste-nationality">
                                    <span class="nationality-badge">
                                        <?php echo esc_html($nationalite); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($roles && is_array($roles)): ?>
                                <div class="artiste-roles">
                                    <?php foreach ($roles as $role): ?>
                                        <span class="role-badge <?php echo esc_html($role); ?>"><?php echo esc_html($role); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="artiste-content">
                    <div class="artiste-main">
                        <?php
                        $biographie = get_field('biographie_artiste');
                        if ($biographie): ?>
                            <section class="artiste-bio">
                                <h2>Biographie</h2>
                                <div class="bio-content">
                                    <?php echo wp_kses_post($biographie); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        // Get books by this artist
                        global $wpdb;
                        $current_artist_id = get_the_ID();
                        $related_book_ids = $wpdb->get_col($wpdb->prepare(
                            "SELECT DISTINCT post_id FROM {$wpdb->postmeta}
                            WHERE meta_key LIKE %s AND meta_value = %d",
                            $wpdb->esc_like('equipe_creative_') . '%_artiste',
                            $current_artist_id
                        ));

                        $books_by_artist = array();
                        if (!empty($related_book_ids)) {
                            $books_by_artist = get_posts(array(
                                'post_type' => 'livre',
                                'post__in' => $related_book_ids,
                                'posts_per_page' => -1,
                                'orderby' => 'post__in',
                                'post_status' => 'publish'
                            ));
                        }

                        if ($books_by_artist): ?>
                            <section class="artiste-works">
                                <h2>Livres sur lesquels cet artiste a travaillé</h2>
                                <div class="works-grid">
                                    <?php foreach ($books_by_artist as $book):
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
                                                <?php if ($photo_devant): ?>
                                                    <img src="<?php echo esc_url($photo_devant['url']); ?>"
                                                        alt="<?php echo esc_attr($photo_devant['alt']); ?>">
                                                <?php else: ?>
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
                                                <?php if ($artist_role): ?>
                                                    <span class="role-badge <?php echo esc_html($artist_role); ?>">
                                                        <?php echo esc_html($artist_role); ?>
                                                </span>
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
                                <?php if ($nom && $prenom): ?>
                                    <li>
                                        <strong>Nom réel:</strong> <?php echo esc_html($prenom . ' ' . $nom); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($nom_dartiste): ?>
                                    <li>
                                        <strong>Nom d'artiste:</strong> <?php echo esc_html($nom_dartiste); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($date_naissance): ?>
                                    <li>
                                        <strong>Né(e):</strong> <?php echo esc_html($date_naissance); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($deces): ?>
                                    <li>
                                        <strong>Décédé(e):</strong> <?php echo esc_html($deces); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($nationalite): ?>
                                    <li>
                                        <strong>Nationalité:</strong> <?php echo esc_html($nationalite); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($roles && is_array($roles)): ?>
                                    <li>
                                        <strong>Rôles:</strong> <?php echo esc_html(implode(', ', $roles)); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($books_by_artist): ?>
                                    <li>
                                        <strong>Œuvres:</strong> <?php echo count($books_by_artist); ?> livre(s)
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php
                        $site_web = get_field('site_web');
                        $instagram = get_field('instagram');
                        if ($site_web || $instagram): ?>
                            <div class="artiste-links">
                                <h3>Liens</h3>
                                <div class="links-list">
                                    <?php if ($site_web): ?>
                                        <a href="<?php echo esc_url($site_web); ?>" class="external-link" target="_blank"
                                            rel="noopener">
                                            <span class="dashicons dashicons-admin-links"></span> Site web
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($instagram): ?>
                                        <a href="<?php echo esc_url($instagram); ?>" class="external-link" target="_blank"
                                            rel="noopener">
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



<?php get_footer(); ?>