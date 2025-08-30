<?php
/**
 * Single template for Collection custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-collection'); ?>>
                <header class="collection-header">
                    <div class="collection-hero">
                        <div class="collection-image">
                            <?php
                            $logo = get_field('logo_collection');
                            if ($logo) : ?>
                                <img src="<?php echo esc_url($logo['url']); ?>" 
                                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                                     class="collection-logo">
                            <?php else : ?>
                                <div class="no-image-placeholder">
                                    <span class="dashicons dashicons-book-alt"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="collection-info">
                            <h1 class="collection-title">
                                <?php 
                                $nom = get_field('nom_collection');
                                echo $nom ? esc_html($nom) : get_the_title(); 
                                ?>
                            </h1>
                            
                            <?php
                            $date_sortie = get_field('date_de_sortie_collection');
                            $date_fin = get_field('date_de_fin_collection');
                            $etat = get_field('etat_collection');
                            ?>
                            
                            <?php if ($date_sortie || $date_fin) : ?>
                                <div class="collection-dates">
                                    <?php if ($date_sortie) : ?>
                                        <div class="date-item">
                                            <span class="date-label">Date de sortie:</span>
                                            <span class="date-value"><?php echo esc_html($date_sortie); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($date_fin) : ?>
                                        <div class="date-item">
                                            <span class="date-label">Date de fin:</span>
                                            <span class="date-value"><?php echo esc_html($date_fin); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($etat) : ?>
                                <div class="collection-status">
                                    <span class="status-badge status-<?php echo esc_attr(strtolower($etat)); ?>">
                                        <?php echo esc_html($etat); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <div class="collection-content">
                    <div class="collection-main">
                        <?php
                        $resume = get_field('resume_collection');
                        if ($resume) : ?>
                            <section class="collection-summary">
                                <h2>Résumé de la collection</h2>
                                <div class="summary-content">
                                    <?php echo wp_kses_post($resume); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        // Get books in this collection
                        $books_in_collection = get_posts(array(
                            'post_type' => 'livre',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'collection',
                                    'value' => get_the_ID(),
                                    'compare' => '='
                                )
                            )
                        ));
                        
                        if ($books_in_collection) : ?>
                            <section class="collection-books">
                                <h2>Livres de la collection</h2>
                                <div class="books-grid">
                                    <?php foreach ($books_in_collection as $book) : 
                                        $photo_devant = get_field('photo_devant', $book->ID);
                                        $titre = get_field('titre_livre', $book->ID);
                                        $date_sortie_livre = get_field('date_sortie_livre', $book->ID);
                                        $n_sortie = get_field('n_sortie', $book->ID);
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
                                                <?php if ($n_sortie) : ?>
                                                    <div class="book-number">N° <?php echo esc_html($n_sortie); ?></div>
                                                <?php endif; ?>
                                                <?php if ($date_sortie_livre) : ?>
                                                    <div class="book-date"><?php echo esc_html($date_sortie_livre); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="collection-sidebar">
                        <div class="collection-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($date_sortie) : ?>
                                    <li>
                                        <strong>Sortie:</strong> <?php echo esc_html($date_sortie); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($date_fin) : ?>
                                    <li>
                                        <strong>Fin:</strong> <?php echo esc_html($date_fin); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($etat) : ?>
                                    <li>
                                        <strong>État:</strong> <?php echo esc_html($etat); ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($books_in_collection) : ?>
                                    <li>
                                        <strong>Nombre de livres:</strong> <?php echo count($books_in_collection); ?>
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
