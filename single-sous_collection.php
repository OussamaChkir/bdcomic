<?php
/**
 * Single template for Sous Collection custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-sous-collection'); ?>>
                <div class="sous-collection-header">
                    <div class="sous-collection-hero">
                        <div class="sous-collection-image">
                            <?php
                            $logo = get_field('logo_sous_collection');
                            if ($logo): ?>
                                <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>"
                                    class="sous-collection-logo">
                            <?php else: ?>
                                <div class="no-image-placeholder">
                                    <span class="dashicons dashicons-index-card"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sous-collection-info">
                            <h1 class="sous-collection-title">
                                <?php
                                $nom = get_field('nom_sous_collection');
                                echo $nom ? esc_html($nom) : get_the_title();
                                ?>
                            </h1>

                            <?php
                            // Get parent collection
                            $collection_parent = get_field('collection_parent');
                            if ($collection_parent): ?>
                                <div class="sous-collection-parent">
                                    <span class="parent-label">Collection parente:</span>
                                    <a href="<?php echo get_permalink($collection_parent->ID); ?>" class="parent-link">
                                        <?php
                                        $nom_collection = get_field('nom_collection', $collection_parent->ID);
                                        echo $nom_collection ? esc_html($nom_collection) : esc_html($collection_parent->post_title);
                                        ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php
                            $date_sortie = get_field('date_de_sortie_sous_collection');
                            $date_fin = get_field('date_de_fin_sous_collection');
                            $etat = get_field('etat_sous_collection');
                            ?>

                            <?php if ($date_sortie || $date_fin): ?>
                                <div class="sous-collection-dates">
                                    <?php if ($date_sortie): ?>
                                        <div class="date-item">
                                            <span class="date-label">Date de sortie:</span>
                                            <span class="date-value"><?php echo esc_html($date_sortie); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($date_fin): ?>
                                        <div class="date-item">
                                            <span class="date-label">Date de fin:</span>
                                            <span class="date-value"><?php echo esc_html($date_fin); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($etat): ?>
                                <div class="sous-collection-status">
                                    <span class="status-badge status-<?php echo esc_attr(str_replace(' ', '-', strtolower($etat))); ?>">
                                        <?php echo esc_html($etat); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (is_user_logged_in()) : ?>
                                <div class="book-actions"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="sous-collection-content">
                    <div class="sous-collection-main">
                        <?php
                        // Get books in this sous collection
                        $books_in_sous_collection = get_posts(array(
                            'post_type' => 'livre',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'sous_collection',
                                    'value' => get_the_ID(),
                                    'compare' => '='
                                )
                            ),
                            'meta_key' => 'n_sortie',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                        ));

                        if ($books_in_sous_collection): ?>
                            <section class="sous-collection-books">
                                <h2>Livres de la sous-collection</h2>
                                <div class="books-grid">
                                    <?php foreach ($books_in_sous_collection as $book):
                                        $photo_devant = get_field('photo_devant', $book->ID);
                                        $titre = get_field('titre_livre', $book->ID);
                                        $date_sortie_livre = get_field('date_sortie_livre', $book->ID);
                                        $n_sortie = get_field('n_sortie', $book->ID);
                                        ?>
                                        <div class="book-item">
                                            <div class="book-cover">
                                                <?php if ($photo_devant): ?>
                                                    <img src="<?php echo esc_url($photo_devant['url']); ?>"
                                                        alt="<?php echo esc_attr($photo_devant['alt']); ?>">
                                                <?php else: ?>
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
                                                <?php if ($n_sortie): ?>
                                                    <div class="book-number">N° <?php echo esc_html($n_sortie); ?></div>
                                                <?php endif; ?>
                                                <?php if ($date_sortie_livre): ?>
                                                    <div class="book-date"><?php echo esc_html($date_sortie_livre); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="sous-collection-sidebar">
                        <div class="sous-collection-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($collection_parent): ?>
                                    <li>
                                        <strong>Collection:</strong>
                                        <a href="<?php echo get_permalink($collection_parent->ID); ?>">
                                            <?php
                                            $nom_collection = get_field('nom_collection', $collection_parent->ID);
                                            echo $nom_collection ? esc_html($nom_collection) : esc_html($collection_parent->post_title);
                                            ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($date_sortie): ?>
                                    <li>
                                        <strong>Sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($date_fin): ?>
                                    <li>
                                        <strong>Fin:</strong> <?php echo esc_html($date_fin); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($etat): ?>
                                    <li>
                                        <strong>État:</strong> <?php echo esc_html($etat); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($books_in_sous_collection): ?>
                                    <li>
                                        <strong>Nombre de livres:</strong> <?php echo count($books_in_sous_collection); ?>
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

