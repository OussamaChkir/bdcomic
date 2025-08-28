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

<style>
.single-collection {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 0;
}

.collection-header {
    margin-bottom: 3rem;
}

.collection-hero {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.collection-image {
    flex-shrink: 0;
}

.collection-logo {
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

.collection-info {
    flex-grow: 1;
}

.collection-title {
    font-size: 2.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    line-height: 1.2;
}

.collection-dates {
    margin-bottom: 1rem;
}

.date-item {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.date-label {
    font-weight: 600;
    color: #666;
    margin-right: 0.5rem;
}

.date-value {
    color: #333;
}

.collection-status {
    margin-bottom: 1rem;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-en-cours {
    background: #e3f2fd;
    color: #1976d2;
}

.status-terminee {
    background: #e8f5e8;
    color: #388e3c;
}

.status-abandonne {
    background: #ffebee;
    color: #d32f2f;
}

.collection-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.collection-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.collection-summary h2,
.collection-books h2 {
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.collection-summary {
    margin-bottom: 3rem;
}

.summary-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
}

.collection-books {
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

.book-number,
.book-date {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.collection-sidebar {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.collection-meta h3 {
    font-size: 1.3rem;
    margin: 0 0 1rem 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
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

@media (max-width: 768px) {
    .collection-hero {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .collection-title {
        font-size: 2rem;
    }
    
    .collection-content {
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
