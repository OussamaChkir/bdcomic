<?php
/**
 * AJAX Handlers for Archive Search and Autocomplete
 *
 * @package bdcomic_theme
 */

// Archive Search AJAX Functions
function archive_search_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'archive_search_nonce')) {
        wp_die('Security check failed');
    }

    $post_type = sanitize_text_field($_POST['post_type']);
    $search_data = $_POST['search'];
    $page = intval($_POST['page']);
    $posts_per_page = get_option('posts_per_page', 10);

    // Build query args
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'meta_query' => array('relation' => 'AND'),
        'tax_query' => array()
    );

    // Add search term
    if (!empty($search_data['search_term'])) {
        $search_term = sanitize_text_field($search_data['search_term']);
        $args['s'] = $search_term;
    }

    // Add filters based on post type
    if (!empty($search_data['filters'])) {
        $filters = $search_data['filters'];

        switch ($post_type) {
            case 'artiste':
                if (!empty($filters['nationality'])) {
                    $args['meta_query'][] = array(
                        'key' => 'nationalite_artiste',
                        'value' => sanitize_text_field($filters['nationality']),
                        'compare' => '='
                    );
                }
                if (!empty($filters['role'])) {
                    $args['meta_query'][] = array(
                        'key' => 'roles_artiste',
                        'value' => '"' . sanitize_text_field($filters['role']) . '"',
                        'compare' => 'LIKE'
                    );
                }
                break;

            case 'collection':
                if (!empty($filters['status'])) {
                    $args['meta_query'][] = array(
                        'key' => 'etat_collection',
                        'value' => sanitize_text_field($filters['status']),
                        'compare' => '='
                    );
                }
                if (!empty($filters['publisher'])) {
                    // Relationship field stores IDs; find editor by exact title then match IDs
                    $publisher_term = sanitize_text_field($filters['publisher']);
                    $publisher_query = get_posts(array(
                        'post_type' => 'editeur',
                        'posts_per_page' => -1,
                        's' => $publisher_term,
                        'fields' => 'ids',
                    ));
                    if (!empty($publisher_query)) {
                        // Filter to exact title match to handle hyphens and special chars
                        $matching_ids = array();
                        foreach ($publisher_query as $publisher_id) {
                            if (get_the_title($publisher_id) === $publisher_term) {
                                $matching_ids[] = $publisher_id;
                            }
                        }
                        if (!empty($matching_ids)) {
                            $args['meta_query'][] = array(
                                'key' => 'editeur_collection',
                                'value' => $matching_ids,
                                'compare' => 'IN'
                            );
                        }
                    }
                }
                break;

            case 'editeur':
                if (!empty($filters['country'])) {
                    $args['meta_query'][] = array(
                        'key' => 'pays_editeur',
                        'value' => sanitize_text_field($filters['country']),
                        'compare' => '='
                    );
                }
                break;

            case 'livre':
                if (!empty($filters['publisher'])) {
                    $publisher_term = sanitize_text_field($filters['publisher']);
                    $publisher_query = get_posts(array(
                        'post_type' => 'editeur',
                        'posts_per_page' => -1,
                        's' => $publisher_term,
                        'fields' => 'ids',
                    ));
                    if (!empty($publisher_query)) {
                        $matching_ids = array();
                        foreach ($publisher_query as $publisher_id) {
                            if (get_the_title($publisher_id) === $publisher_term) {
                                $matching_ids[] = $publisher_id;
                            }
                        }
                        if (!empty($matching_ids)) {
                            $args['meta_query'][] = array(
                                'key' => 'maison_d\'edition',
                                'value' => $matching_ids,
                                'compare' => 'IN'
                            );
                        }
                    }
                }
                if (!empty($filters['collection'])) {
                    $collection_term = sanitize_text_field($filters['collection']);
                    $collection_query = get_posts(array(
                        'post_type' => 'collection',
                        'posts_per_page' => -1,
                        's' => $collection_term,
                        'fields' => 'ids',
                    ));
                    if (!empty($collection_query)) {
                        $matching_ids = array();
                        foreach ($collection_query as $collection_id) {
                            if (get_the_title($collection_id) === $collection_term) {
                                $matching_ids[] = $collection_id;
                            }
                        }
                        if (!empty($matching_ids)) {
                            $args['meta_query'][] = array(
                                'key' => 'collection',
                                'value' => $matching_ids,
                                'compare' => 'IN'
                            );
                        }
                    }
                }
                if (!empty($filters['variant'])) {
                    $args['meta_query'][] = array(
                        'key' => 'variante',
                        'value' => '1',
                        'compare' => '='
                    );
                }
                break;

            case 'guide_lecture':
                if (!empty($filters['collection'])) {
                    $collection_id = intval($filters['collection']);
                    if ($collection_id > 0) {
                        $args['meta_query'][] = array(
                            'key' => 'collection_guide',
                            'value' => $collection_id,
                            'compare' => '='
                        );
                    }
                }
                break;
        }
    }

    // Remove the relation if no meta queries were added
    if (count($args['meta_query']) === 1) {
        unset($args['meta_query']['relation']);
    }

    // Add alphabetical sort if requested
    if (!empty($search_data['filters']['sort'])) {
        $sort_order = sanitize_text_field($search_data['filters']['sort']);
        if ($sort_order === 'asc' || $sort_order === 'desc') {
            $args['orderby'] = 'title';
            $args['order'] = strtoupper($sort_order);
        }
    }

    // Execute query
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();

        // Start the loop
        while ($query->have_posts()) {
            $query->the_post();

            // Include the appropriate template part based on post type
            switch ($post_type) {
                case 'artiste':
                    include(get_template_directory() . '/template-parts/content-artiste.php');
                    break;
                case 'collection':
                    include(get_template_directory() . '/template-parts/content-collection.php');
                    break;
                case 'editeur':
                    include(get_template_directory() . '/template-parts/content-editeur.php');
                    break;
                case 'livre':
                    include(get_template_directory() . '/template-parts/content-livre.php');
                    break;
                default:
                    get_template_part('template-parts/content', get_post_type());
            }
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        // Generate pagination
        $pagination = '';
        if ($query->max_num_pages > 1) {
            $pagination = paginate_links(array(
                'base' => '#',
                'format' => '?paged=%#%',
                'current' => $page,
                'total' => $query->max_num_pages,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
                'type' => 'array'
            ));

            if ($pagination) {
                $pagination = '<div class="archive-pagination">' . implode('', $pagination) . '</div>';
            }
        }

        wp_send_json_success(array(
            'html' => $html,
            'pagination' => $pagination,
            'total_results' => $query->found_posts,
            'current_page' => $page,
            'max_pages' => $query->max_num_pages
        ));
    } else {
        wp_send_json_success(array(
            'html' => '<div class="no-posts"><p>Aucun résultat trouvé.</p></div>',
            'pagination' => '',
            'total_results' => 0,
            'current_page' => $page,
            'max_pages' => 0
        ));
    }
}
add_action('wp_ajax_archive_search', 'archive_search_ajax');
add_action('wp_ajax_nopriv_archive_search', 'archive_search_ajax');

// Archive Autocomplete AJAX Function
function archive_autocomplete_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'archive_search_nonce')) {
        wp_die('Security check failed');
    }

    $post_type = sanitize_text_field($_POST['post_type']);
    $term = sanitize_text_field($_POST['term']);
    $suggestions = array();

    if (strlen($term) >= 2) {
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 10,
            's' => $term,
            'orderby' => 'title',
            'order' => 'ASC'
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $title = get_the_title();
                $suggestions[] = array(
                    'label' => $title,
                    'value' => $title
                );
            }
            wp_reset_postdata();
        }
    }

    wp_send_json_success($suggestions);
}
add_action('wp_ajax_archive_autocomplete', 'archive_autocomplete_ajax');
add_action('wp_ajax_nopriv_archive_autocomplete', 'archive_autocomplete_ajax');
