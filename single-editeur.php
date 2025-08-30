<?php
/**
 * Single template for Editeur custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-editeur'); ?>>
                <header class="editeur-header">
                    <div class="editeur-hero">
                        <div class="editeur-image">
                            <?php
                            $logo = get_field('logo_editeur');
                            if ($logo) : ?>
                                <img src="<?php echo esc_url($logo['url']); ?>" 
                                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                                     class="editeur-logo">
                            <?php else : ?>
                                <div class="no-image-placeholder">
                                    <span class="dashicons dashicons-building"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="editeur-info">
                            <h1 class="editeur-title">
                                <?php 
                                $nom = get_field('nom_editeur');
                                echo $nom ? esc_html($nom) : get_the_title(); 
                                ?>
                            </h1>
                            
                            <?php
                            $description = get_field('description_editeur');
                            if ($description) : ?>
                                <div class="editeur-description">
                                    <?php echo wp_kses_post(wp_trim_words($description, 50, '...')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <div class="editeur-content">
                    <div class="editeur-main">
                        <?php if ($description) : ?>
                            <section class="editeur-full-description">
                                <h2>À propos de l'éditeur</h2>
                                <div class="description-content">
                                    <?php echo wp_kses_post($description); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        // Get books published by this editor
                        $books_by_editeur = get_posts(array(
                            'post_type' => 'livre',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'maison_d\'edition',
                                    'value' => get_the_ID(),
                                    'compare' => '='
                                )
                            )
                        ));
                        
                        if ($books_by_editeur) : ?>
                            <section class="editeur-books">
                                <h2>Livres publiés</h2>
                                <div class="books-grid">
                                    <?php foreach ($books_by_editeur as $book) : 
                                        $photo_devant = get_field('photo_devant', $book->ID);
                                        $titre = get_field('titre_livre', $book->ID);
                                        $date_sortie = get_field('date_sortie_livre', $book->ID);
                                        $n_sortie = get_field('n_sortie', $book->ID);
                                        $collection = get_field('collection', $book->ID);
                                    ?>
                                        <div class="book-item">
                                            <div class="book-cover">
                                                <?php if ($photo_devant) : ?>
                                                    <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                                                         alt="<?php echo esc_attr($photo_devant['alt']); ?>">
                                                <?php else : ?>
                                                    <div class="no-cover-placeholder">
                                                        <span class="dashicons dashicons-book"></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="book-info">
                                                <h3 class="book-title">
                                                    <a href="<?php echo get_permalink($book->ID); ?>">
                                                        <?php echo $titre ? esc_html($titre) : esc_html($book->post_title); ?>
                                                    </a>
                                                </h3>
                                                <?php if ($collection) : ?>
                                                    <div class="book-collection">
                                                        <a href="<?php echo get_permalink($collection->ID); ?>">
                                                            <?php echo esc_html($collection->post_title); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($n_sortie) : ?>
                                                    <div class="book-number">N° <?php echo esc_html($n_sortie); ?></div>
                                                <?php endif; ?>
                                                <?php if ($date_sortie) : ?>
                                                    <div class="book-date"><?php echo esc_html($date_sortie); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="editeur-sidebar">
                        <div class="editeur-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($books_by_editeur) : ?>
                                    <li>
                                        <strong>Livres publiés:</strong> <?php echo count($books_by_editeur); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php
                        $liens = get_field('liens');
                        if ($liens && is_array($liens)) : ?>
                            <div class="editeur-links">
                                <h3>Liens utiles</h3>
                                <div class="links-list">
                                    <?php foreach ($liens as $lien) : ?>
                                        <?php if (!empty($lien['label']) && !empty($lien['url'])) : ?>
                                            <a href="<?php echo esc_url($lien['url']); ?>" 
                                               class="external-link" 
                                               target="_blank" 
                                               rel="noopener">
                                                <span class="dashicons dashicons-external"></span>
                                                <?php echo esc_html($lien['label']); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
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
