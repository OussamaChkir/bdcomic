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

<style>
.single-editeur {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 0;
}

.editeur-header {
    margin-bottom: 3rem;
}

.editeur-hero {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.editeur-image {
    flex-shrink: 0;
}

.editeur-logo {
    width: 200px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.no-image-placeholder {
    width: 200px;
    height: 200px;
    background: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.no-image-placeholder .dashicons {
    font-size: 4rem;
    color: #ccc;
}

.editeur-info {
    flex-grow: 1;
}

.editeur-title {
    font-size: 2.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    line-height: 1.2;
}

.editeur-description {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #555;
}

.editeur-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.editeur-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.editeur-full-description h2,
.editeur-books h2 {
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.editeur-full-description {
    margin-bottom: 3rem;
}

.description-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
}

.editeur-books {
    margin-top: 2rem;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.book-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.book-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.book-cover {
    height: 250px;
    overflow: hidden;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-cover-placeholder {
    width: 100%;
    height: 100%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-cover-placeholder .dashicons {
    font-size: 3rem;
    color: #ccc;
}

.book-info {
    padding: 1rem;
}

.book-title {
    font-size: 1rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}

.book-title a {
    color: #333;
    text-decoration: none;
}

.book-title a:hover {
    color: #007cba;
}

.book-collection {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.book-collection a {
    color: #007cba;
    text-decoration: none;
}

.book-collection a:hover {
    text-decoration: underline;
}

.book-number,
.book-date {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.editeur-sidebar {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.editeur-meta h3,
.editeur-links h3 {
    font-size: 1.3rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.editeur-links {
    margin-top: 2rem;
}

.meta-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.meta-list li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 1rem;
}

.meta-list li:last-child {
    border-bottom: none;
}

.meta-list strong {
    color: #666;
    margin-right: 0.5rem;
}

.links-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.external-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.external-link:hover {
    background: #e9ecef;
    color: #333;
    border-color: #dee2e6;
}

.external-link .dashicons {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .editeur-hero {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .editeur-title {
        font-size: 2rem;
    }
    
    .editeur-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .book-cover {
        height: 200px;
    }
}
</style>

<?php get_footer(); ?>
