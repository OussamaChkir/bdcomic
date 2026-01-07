<?php
/**
 * Single template for Editeur custom post type
 * 
 * @package bdcomic_theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-editeur'); ?>>
                <div class="editeur-header">
                    <div class="editeur-hero">
                        <div class="editeur-image">
                            <?php
                            $logo = get_field('logo_editeur');
                            if ($logo): ?>
                                <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>"
                                    class="editeur-logo">
                            <?php else: ?>
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
                            if ($description): ?>
                                <div class="editeur-description">
                                    <?php echo wp_kses_post(wp_trim_words($description, 50, '...')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="editeur-content">
                    <div class="editeur-main">
                        <?php if ($description): ?>
                            <section class="editeur-full-description">
                                <h2>À propos de l'éditeur</h2>
                                <div class="description-content">
                                    <?php echo wp_kses_post($description); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php
                        // Get books published by this editor
                        $editor_id = get_the_ID();
                        $books_by_editeur = get_posts(array(
                            'post_type' => 'livre',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                'relation' => 'OR',
                                // Check Curly Quote Key
                                array(
                                    'key' => 'maison_d’edition',
                                    'value' => $editor_id,
                                    'compare' => '='
                                ),
                                array(
                                    'key' => 'maison_d’edition',
                                    'value' => '"' . $editor_id . '"',
                                    'compare' => 'LIKE'
                                ),
                                // Check Straight Quote Key (fallback)
                                array(
                                    'key' => 'maison_d\'edition',
                                    'value' => $editor_id,
                                    'compare' => '='
                                ),
                                array(
                                    'key' => 'maison_d\'edition',
                                    'value' => '"' . $editor_id . '"',
                                    'compare' => 'LIKE'
                                )
                            )
                        ));

                        if ($books_by_editeur):
                            // Group books by collection and sub-collection
                            $books_grouped = array();
                            foreach ($books_by_editeur as $book) {
                                $collection = get_field('collection', $book->ID);
                                $sous_collection = get_field('sous_collection', $book->ID);

                                if ($collection) {
                                    $collection_id = $collection->ID;
                                    if (!isset($books_grouped[$collection_id])) {
                                        $books_grouped[$collection_id] = array(
                                            'info' => $collection,
                                            'books' => array(),
                                            'subs' => array()
                                        );
                                    }

                                    if ($sous_collection) {
                                        $sub_id = 0;
                                        $sub_name = '';
                                        if (is_object($sous_collection)) {
                                            $sub_id = $sous_collection->ID;
                                            $sub_name = $sous_collection->post_title;
                                        } elseif (is_array($sous_collection)) {
                                            $sub_id = $sous_collection['ID'];
                                            $sub_name = $sous_collection['post_title'];
                                        } else {
                                            $sub_id = (int) $sous_collection;
                                            $sub_name = get_the_title($sub_id);
                                        }

                                        if ($sub_id) {
                                            if (!isset($books_grouped[$collection_id]['subs'][$sub_id])) {
                                                $books_grouped[$collection_id]['subs'][$sub_id] = array(
                                                    'name' => $sub_name,
                                                    'books' => array()
                                                );
                                            }
                                            $books_grouped[$collection_id]['subs'][$sub_id]['books'][] = $book;
                                        } else {
                                            $books_grouped[$collection_id]['books'][] = $book;
                                        }
                                    } else {
                                        $books_grouped[$collection_id]['books'][] = $book;
                                    }
                                } else {
                                    if (!isset($books_grouped['others'])) {
                                        $books_grouped['others'] = array(
                                            'info' => null,
                                            'books' => array(),
                                            'subs' => array()
                                        );
                                    }
                                    $books_grouped['others']['books'][] = $book;
                                }
                            }

                            // Sort collections by name
                            $others = isset($books_grouped['others']) ? $books_grouped['others'] : null;
                            unset($books_grouped['others']);

                            uasort($books_grouped, function ($a, $b) {
                                return strcmp($a['info']->post_title, $b['info']->post_title);
                            });

                            // Add others back at the end
                            if ($others) {
                                $books_grouped['others'] = $others;
                            }

                            $current_user_id = get_current_user_id();
                            ?>
                            <section class="editeur-books">
                                <h2>Livres publiés</h2>

                                <?php foreach ($books_grouped as $group_id => $group): ?>
                                    <div class="collection-group-section">
                                        <?php if ($group['info']): ?>
                                            <h3 class="collection-group-title">
                                                <a href="<?php echo get_permalink($group['info']->ID); ?>">
                                                    <?php echo esc_html($group['info']->post_title); ?>
                                                </a>
                                            </h3>
                                        <?php elseif (count($books_grouped) > 1): ?>
                                            <h3 class="collection-group-title"><?php _e('Autres livres', 'bdcomic_theme'); ?></h3>
                                        <?php endif; ?>

                                        <!-- Direct collection books -->
                                        <?php if (!empty($group['books'])): ?>
                                            <div class="books-grid">
                                                <?php foreach ($group['books'] as $book):
                                                    $photo_devant = get_field('photo_devant', $book->ID);
                                                    $titre = get_field('titre_livre', $book->ID);
                                                    $date_sortie = get_field('date_sortie_livre', $book->ID);
                                                    $n_sortie = get_field('n_sortie', $book->ID);
                                                    $collection = get_field('collection', $book->ID);
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
                                                            <?php if ($collection): ?>
                                                                <div class="book-collection">
                                                                    <a href="<?php echo get_permalink($collection->ID); ?>">
                                                                        <?php echo esc_html($collection->post_title); ?>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($n_sortie): ?>
                                                                <div class="book-number">N° <?php echo esc_html($n_sortie); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ($date_sortie): ?>
                                                                <div class="book-date"><?php echo esc_html($date_sortie); ?></div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php if (is_user_logged_in()): ?>
                                                            <div class="book-actions">
                                                                <div class="book-quick-actions">
                                                                    <?php
                                                                    $post_id = $book->ID;
                                                                    // Quick owned button
                                                                    $is_owned = is_book_in_user_list($current_user_id, $post_id, 'owned');
                                                                    ?>
                                                                    <button class="book-quick-action <?php echo $is_owned ? 'active' : ''; ?>"
                                                                        data-post-id="<?php echo $post_id; ?>" data-list-type="owned"
                                                                        data-post-type="livre" data-bs-toggle="tooltip"
                                                                        title="<?php echo $is_owned ? __('Marquer comme non possédé', 'bdcomic_theme') : __('Marquer comme possédé', 'bdcomic_theme'); ?>">
                                                                        <span
                                                                            class="dashicons <?php echo $is_owned ? 'dashicons-star-filled' : 'dashicons-star-empty'; ?>"></span>
                                                                    </button>

                                                                    <?php
                                                                    // Quick read button
                                                                    $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                                                                    ?>
                                                                    <button class="book-quick-action <?php echo $is_read ? 'active' : ''; ?>"
                                                                        data-post-id="<?php echo $post_id; ?>" data-list-type="read"
                                                                        data-post-type="livre" data-bs-toggle="tooltip"
                                                                        title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                                                                        <span
                                                                            class="dashicons <?php echo $is_read ? 'dashicons-yes-alt' : 'dashicons-yes'; ?>"></span>
                                                                    </button>

                                                                    <?php
                                                                    // Quick wishlist button
                                                                    $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                                                                    ?>
                                                                    <button
                                                                        class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>"
                                                                        data-post-id="<?php echo $post_id; ?>" data-list-type="wishlist"
                                                                        data-post-type="livre" data-bs-toggle="tooltip"
                                                                        title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                                                        <span
                                                                            class="dashicons <?php echo $in_wishlist ? 'dashicons-heart' : 'dashicons-heart'; ?>"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Sub collections -->
                                        <?php if (!empty($group['subs'])): ?>
                                            <?php foreach ($group['subs'] as $sub_id => $sub): ?>
                                                <div class="sub-collection-group">
                                                    <h4 class="sub-collection-title"><?php echo esc_html($sub['name']); ?></h4>
                                                    <div class="books-grid">
                                                        <?php foreach ($sub['books'] as $book):
                                                            $photo_devant = get_field('photo_devant', $book->ID);
                                                            $titre = get_field('titre_livre', $book->ID);
                                                            $date_sortie = get_field('date_sortie_livre', $book->ID);
                                                            $n_sortie = get_field('n_sortie', $book->ID);
                                                            $collection = get_field('collection', $book->ID);
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
                                                                    <?php if ($collection): ?>
                                                                        <div class="book-collection">
                                                                            <a href="<?php echo get_permalink($collection->ID); ?>">
                                                                                <?php echo esc_html($collection->post_title); ?>
                                                                            </a>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if ($n_sortie): ?>
                                                                        <div class="book-number">N° <?php echo esc_html($n_sortie); ?></div>
                                                                    <?php endif; ?>
                                                                    <?php if ($date_sortie): ?>
                                                                        <div class="book-date"><?php echo esc_html($date_sortie); ?></div>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <?php if (is_user_logged_in()): ?>
                                                                    <div class="book-actions">
                                                                        <div class="book-quick-actions">
                                                                            <?php
                                                                            $post_id = $book->ID;
                                                                            // Quick owned button
                                                                            $is_owned = is_book_in_user_list($current_user_id, $post_id, 'owned');
                                                                            ?>
                                                                            <button
                                                                                class="book-quick-action <?php echo $is_owned ? 'active' : ''; ?>"
                                                                                data-post-id="<?php echo $post_id; ?>" data-list-type="owned"
                                                                                data-post-type="livre" data-bs-toggle="tooltip"
                                                                                title="<?php echo $is_owned ? __('Marquer comme non possédé', 'bdcomic_theme') : __('Marquer comme possédé', 'bdcomic_theme'); ?>">
                                                                                <span
                                                                                    class="dashicons <?php echo $is_owned ? 'dashicons-star-filled' : 'dashicons-star-empty'; ?>"></span>
                                                                            </button>

                                                                            <?php
                                                                            // Quick read button
                                                                            $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                                                                            ?>
                                                                            <button
                                                                                class="book-quick-action <?php echo $is_read ? 'active' : ''; ?>"
                                                                                data-post-id="<?php echo $post_id; ?>" data-list-type="read"
                                                                                data-post-type="livre" data-bs-toggle="tooltip"
                                                                                title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                                                                                <span
                                                                                    class="dashicons <?php echo $is_read ? 'dashicons-yes-alt' : 'dashicons-yes'; ?>"></span>
                                                                            </button>

                                                                            <?php
                                                                            // Quick wishlist button
                                                                            $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                                                                            ?>
                                                                            <button
                                                                                class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>"
                                                                                data-post-id="<?php echo $post_id; ?>" data-list-type="wishlist"
                                                                                data-post-type="livre" data-bs-toggle="tooltip"
                                                                                title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                                                                <span
                                                                                    class="dashicons <?php echo $in_wishlist ? 'dashicons-heart' : 'dashicons-heart'; ?>"></span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </section>
                        <?php endif; ?>
                    </div>

                    <aside class="editeur-sidebar">
                        <div class="editeur-meta">
                            <h3>Informations</h3>
                            <ul class="meta-list">
                                <?php if ($books_by_editeur): ?>
                                    <li>
                                        <strong>Livres publiés:</strong> <?php echo count($books_by_editeur); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php
                        $liens = get_field('liens');
                        if ($liens && is_array($liens)): ?>
                            <div class="editeur-links">
                                <h3>Liens utiles</h3>
                                <div class="links-list">
                                    <?php foreach ($liens as $lien): ?>
                                        <?php if (!empty($lien['label']) && !empty($lien['url'])): ?>
                                            <a href="<?php echo esc_url($lien['url']); ?>" class="external-link" target="_blank"
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