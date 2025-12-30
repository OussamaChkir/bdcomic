<?php
wp_enqueue_style('block-icon-text', get_template_directory_uri() . '/assets/css/Globals/search.css', array(), '1.0', 'all');
get_header(); ?>

<div class="container">
    <h1>
        <?php printf(__('Résultats de recherche pour : %s', 'bdcomic_theme'), get_search_query()); ?>
    </h1>

    <?php
    // Tableau pour stocker les résultats groupés
    $grouped_results = [];

    // Collecter les posts
    // Collecter les posts
    global $wp_query;
    $args = $wp_query->query_vars;
    $args['posts_per_page'] = -1;
    $search_query = new WP_Query($args);

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            $type = get_post_type();

            // Ajouter dans le bon groupe
            if (!isset($grouped_results[$type])) {
                $grouped_results[$type] = [];
            }

            $grouped_results[$type][] = get_the_ID();
        }
        wp_reset_postdata();
    }

    // Si aucun résultat
    if (empty($grouped_results)) {
        echo '<p>' . __('No results found.', 'bdcomic_theme') . '</p>';
        get_footer();
        return;
    }

    // Fonction pour récupérer l’image ACF par type
    function get_acf_image_by_type($post_id)
    {
        $type = get_post_type($post_id);

        switch ($type) {
            case 'livre':
                return get_field('photo_devant', $post_id);
            case 'collection':
                return get_field('logo_collection', $post_id);
            case 'sous_collection':
                return get_field('logo_sous_collection', $post_id);
            case 'artiste':
                return get_field('photo_artiste', $post_id);
            case 'editeur':
                return get_field('logo_editeur', $post_id);
        }
        return false;
    }
    ?>

    <?php
    // Affichage par type
    foreach ($grouped_results as $type => $posts_ids):
        $type_obj = get_post_type_object($type);
        $type_label = $type_obj ? $type_obj->labels->name : ucfirst($type);
        $count = count($posts_ids);
        ?>

        <h2><?php echo $type_label . " ($count)"; ?></h2>

        <ul class="search-results-list grouped-list">
            <?php foreach ($posts_ids as $post_id):
                $img = get_acf_image_by_type($post_id);

                if ($img && isset($img['sizes']['medium'])) {
                    $img_url = $img['sizes']['medium'];
                } else {
                    $img_url = get_template_directory_uri() . '/assets/img/placeholder/cover.png';
                }
                ?>
                <li class="search-item">
                    <a href="<?php echo get_permalink($post_id); ?>" class="search-item-link">
                        <div class="search-item-img">
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
                        </div>
                        <div class="search-item-info">
                            <h3><?php echo get_the_title($post_id); ?></h3>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endforeach; ?>
</div>

<?php get_footer(); ?>