<?php
/**
 * User Books Management System
 * Handles user's book collections, wishes, and read books
 * 
 * @package bdcomic_theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize user books management
 */
function init_user_books_management()
{
    // Create database tables on theme activation
    add_action('after_switch_theme', 'create_user_books_tables');

    // AJAX handlers
    add_action('wp_ajax_add_to_user_books', 'add_to_user_books_ajax');
    add_action('wp_ajax_remove_from_user_books', 'remove_from_user_books_ajax');
    add_action('wp_ajax_get_user_books', 'get_user_books_ajax');
    add_action('wp_ajax_search_user_wishlist', 'search_user_wishlist_ajax');

    add_action('wp_ajax_report_book_problem', 'report_book_problem_ajax');

    // Enqueue scripts and styles
    add_action('wp_enqueue_scripts', 'enqueue_user_books_scripts');
    add_action('wp_enqueue_scripts', 'enqueue_my_collections_grid_scripts');
}
add_action('init', 'init_user_books_management');

/**
 * Create database tables for user books management
 */
function create_user_books_tables()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Table for user books (wishes, read books, collections)
    $table_name = $wpdb->prefix . 'user_books';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        post_id bigint(20) NOT NULL,
        post_type varchar(20) NOT NULL,
        list_type varchar(20) NOT NULL, -- 'wishlist', 'read', 'collection_wishlist'
        added_date datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_post_list (user_id, post_id, list_type),
        KEY user_id (user_id),
        KEY post_id (post_id),
        KEY list_type (list_type)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Add book to user's list
 */
function add_to_user_books($user_id, $post_id, $list_type)
{
    global $wpdb;

    if (!is_user_logged_in() || $user_id != get_current_user_id()) {
        return false;
    }

    // Validate post exists and is correct type
    $post = get_post($post_id);
    if (!$post || !in_array($post->post_type, ['livre', 'collection'])) {
        return false;
    }

    // Validate list type
    if (!in_array($list_type, ['wishlist', 'read', 'collection_wishlist', 'loaned', 'owned'])) {
        return false;
    }

    $table_name = $wpdb->prefix . 'user_books';

    // Check if already exists
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE user_id = %d AND post_id = %d AND list_type = %s",
        $user_id,
        $post_id,
        $list_type
    ));

    if ($exists) {
        return false; // Already exists
    }

    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'post_type' => $post->post_type,
            'list_type' => $list_type,
            'added_date' => current_time('mysql')
        ),
        array('%d', '%d', '%s', '%s', '%s')
    );

    return $result !== false;
}

/**
 * Remove book from user's list
 */
function remove_from_user_books($user_id, $post_id, $list_type)
{
    global $wpdb;

    if (!is_user_logged_in() || $user_id != get_current_user_id()) {
        return false;
    }

    $table_name = $wpdb->prefix . 'user_books';

    $result = $wpdb->delete(
        $table_name,
        array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'list_type' => $list_type
        ),
        array('%d', '%d', '%s')
    );

    return $result !== false;
}

/**
 * Get user's books from specific list
 */
function get_user_books($user_id, $list_type, $post_type = null)
{
    global $wpdb;

    if (!is_user_logged_in() || $user_id != get_current_user_id()) {
        return array();
    }

    $table_name = $wpdb->prefix . 'user_books';

    $sql = "SELECT post_id, added_date FROM $table_name WHERE user_id = %d AND list_type = %s";
    $args = array($user_id, $list_type);

    if ($post_type) {
        $sql .= " AND post_type = %s";
        $args[] = $post_type;
    }

    $sql .= " ORDER BY added_date DESC";

    $post_ids = $wpdb->get_results($wpdb->prepare($sql, $args));

    $books = array();
    foreach ($post_ids as $item) {
        $post = get_post($item->post_id);
        if ($post && $post->post_status === 'publish') {
            $books[] = array(
                'post' => $post,
                'added_date' => $item->added_date
            );
        }
    }

    return $books;
}

/**
 * Check if book is in user's list
 */
function is_book_in_user_list($user_id, $post_id, $list_type)
{
    global $wpdb;

    if (!is_user_logged_in()) {
        return false;
    }

    $table_name = $wpdb->prefix . 'user_books';

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE user_id = %d AND post_id = %d AND list_type = %s",
        $user_id,
        $post_id,
        $list_type
    ));

    return !empty($exists);
}

/**
 * Get user books statistics
 */
function get_user_books_stats($user_id)
{
    if (!is_user_logged_in() || $user_id != get_current_user_id()) {
        return array();
    }

    $stats = array(
        'wishlist_books' => count(get_user_books($user_id, 'wishlist', 'livre')),
        'read_books' => count(get_user_books($user_id, 'read', 'livre')),
        'owned_books' => count(get_user_books($user_id, 'owned', 'livre')),
        'collection_wishlist' => count(get_user_books($user_id, 'collection_wishlist', 'collection')),
        'loaned_books' => count(get_user_books($user_id, 'loaned', 'livre')),
    );

    return $stats;
}

/**
 * Get statistics for a specific book
 * Returns counts of users who have this book in different lists
 */
function get_book_user_stats($post_id)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'user_books';

    // Initialize stats
    $stats = array(
        'owned' => 0,
        'read' => 0,
        'wishlist' => 0,
        'loaned' => 0
    );

    // Get counts for each list type for this book
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT list_type, COUNT(DISTINCT user_id) as count 
        FROM $table_name 
        WHERE post_id = %d 
        GROUP BY list_type",
        $post_id
    ));

    if ($results) {
        foreach ($results as $row) {
            if (isset($stats[$row->list_type])) {
                $stats[$row->list_type] = intval($row->count);
            }
        }
    }

    return $stats;
}

/**
 * AJAX handler to add book to user's list
 */
function add_to_user_books_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'user_books_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $post_id = intval($_POST['post_id']);
    $list_type = sanitize_text_field($_POST['list_type']);
    $user_id = get_current_user_id();

    $result = add_to_user_books($user_id, $post_id, $list_type);

    if ($result) {
        wp_send_json_success('Book added successfully');
    } else {
        wp_send_json_error('Failed to add book');
    }
}

/**
 * AJAX handler to remove book from user's list
 */
function remove_from_user_books_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'user_books_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $post_id = intval($_POST['post_id']);
    $list_type = sanitize_text_field($_POST['list_type']);
    $user_id = get_current_user_id();

    $result = remove_from_user_books($user_id, $post_id, $list_type);

    if ($result) {
        wp_send_json_success('Book removed successfully');
    } else {
        wp_send_json_error('Failed to remove book');
    }
}

/**
 * AJAX handler to get user's books
 */
function get_user_books_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'user_books_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $list_type = sanitize_text_field($_POST['list_type']);
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : null;
    $user_id = get_current_user_id();

    $books = get_user_books($user_id, $list_type, $post_type);

    wp_send_json_success($books);
}

/**
 * AJAX handler to search user's wishlisted books
 */
function search_user_wishlist_ajax()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'whislisted_book_grid_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    $user_id = get_current_user_id();

    $wishlist_books = get_user_books($user_id, 'wishlist', 'livre');

    if (empty($search_term)) {
        $filtered_books = $wishlist_books;
    } else {
        // Filter books by title or collection
        $filtered_books = array();
        foreach ($wishlist_books as $book_data) {
            $post = $book_data['post'];
            $title = get_field('titre_livre', $post->ID) ?: $post->post_title;
            $collection_id = get_field('collection', $post->ID);
            $collection_name = '';
            if ($collection_id) {
                $collection_name = get_field('nom_collection', $collection_id) ?: get_the_title($collection_id);
            }

            // Check if search term matches title or collection
            if (stripos($title, $search_term) !== false || stripos($collection_name, $search_term) !== false) {
                $filtered_books[] = $book_data;
            }
        }
    }

    // Generate HTML
    ob_start();
    if (!empty($filtered_books)) {
        foreach ($filtered_books as $book_data) {
            $post = $book_data['post'];
            $GLOBALS['current_book_id'] = $post->ID;
            get_template_part('template-parts/content-livre-grid');
        }
    } else {
        echo '<div class="no-books-message"><p>Aucun livre trouvé pour cette recherche.</p></div>';
    }
    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}



/**
 * Enqueue scripts and styles for user books management
 */
function enqueue_user_books_scripts()
{
    if (!is_user_logged_in()) {
        return;
    }

    wp_enqueue_script(
        'user-books-management',
        get_template_directory_uri() . '/assets/js/user-books-management.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_localize_script('user-books-management', 'userBooksData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('user_books_nonce'),
        'reportNonce' => wp_create_nonce('report_book_problem_nonce'),
        'strings' => array(
            'addToWishlist' => __('Ajouter aux souhaits', 'bdcomic_theme'),
            'removeFromWishlist' => __('Retirer des souhaits', 'bdcomic_theme'),
            'markAsRead' => __('Marquer comme lu', 'bdcomic_theme'),
            'markAsUnread' => __('Marquer comme non lu', 'bdcomic_theme'),
            'markAsOwned' => __('Marquer comme possédé', 'bdcomic_theme'),
            'markAsNotOwned' => __('Marquer comme non possédé', 'bdcomic_theme'),
            'addToCollectionWishlist' => __('Ajouter aux souhaits de collection', 'bdcomic_theme'),
            'removeFromCollectionWishlist' => __('Retirer des souhaits de collection', 'bdcomic_theme'),
            'markAsLoaned' => __('Marquer comme prêté', 'bdcomic_theme'),
            'markAsNotLoaned' => __('Marquer comme non prêté', 'bdcomic_theme'),

            'loading' => __('Chargement...', 'bdcomic_theme'),
            'error' => __('Une erreur est survenue', 'bdcomic_theme'),
            'reportProblem' => __('Signaler un problème', 'bdcomic_theme'),
            'reportProblemTitle' => __('Signaler un problème avec ce livre', 'bdcomic_theme'),
            'reportProblemMessage' => __('Décrivez le problème que vous avez rencontré:', 'bdcomic_theme'),
            'reportProblemPlaceholder' => __('Ex: Informations incorrectes, image manquante, erreur dans les détails...', 'bdcomic_theme'),
            'submitReport' => __('Envoyer le signalement', 'bdcomic_theme'),
            'cancel' => __('Annuler', 'bdcomic_theme'),
            'reportSuccess' => __('Problème signalé avec succès. Merci de votre contribution.', 'bdcomic_theme'),
            'reportError' => __('Erreur lors de l\'envoi du signalement', 'bdcomic_theme')
        )
    ));

    wp_enqueue_style(
        'user-books-management',
        get_template_directory_uri() . '/assets/css/user-books-management.css',
        array(),
        '1.0.0'
    );
}

/**
 * Enqueue scripts and styles for my collections grid
 */
function enqueue_my_collections_grid_scripts()
{
    if (!is_user_logged_in()) {
        return;
    }

    wp_enqueue_script(
        'my-collections-grid',
        get_template_directory_uri() . '/assets/js/block-my-collections-grid.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'my-collections-grid',
        get_template_directory_uri() . '/assets/css/ContentElements/ce-my-collections-grid.css',
        array(),
        '1.0.0'
    );
}

/**
 * Enqueue scripts and styles for missing albums grid
 */
function enqueue_missing_albums_grid_scripts()
{
    if (!is_user_logged_in()) {
        return;
    }

    wp_enqueue_script(
        'missing-albums-grid',
        get_template_directory_uri() . '/assets/js/block-missing-albums-grid.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'missing-albums-grid',
        get_template_directory_uri() . '/assets/css/ContentElements/ce-missing-albums-grid.css',
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_missing_albums_grid_scripts');

/**
 * Get list type labels
 */
function get_list_type_labels()
{
    return array(
        'wishlist' => __('Souhaits', 'bdcomic_theme'),
        'read' => __('Lus', 'bdcomic_theme'),
        'owned' => __('Possédés', 'bdcomic_theme'),
        'collection_wishlist' => __('Souhaits de Collection', 'bdcomic_theme'),

        'loaned' => __('Prêtés', 'bdcomic_theme')
    );
}

/**
 * Get list type descriptions
 */
function get_list_type_descriptions()
{
    return array(
        'wishlist' => __('Livres que vous souhaitez lire', 'bdcomic_theme'),
        'read' => __('Livres que vous avez lus', 'bdcomic_theme'),
        'owned' => __('Livres que vous possédez', 'bdcomic_theme'),
        'collection_wishlist' => __('Collections que vous souhaitez suivre', 'bdcomic_theme'),

        'loaned' => __('Livres que vous avez prêtés', 'bdcomic_theme')
    );
}

/**
 * Register shortcode for user books list
 */
function register_user_books_shortcode()
{
    add_shortcode('user_books_list', 'user_books_list_shortcode');
}
add_action('init', 'register_user_books_shortcode');

/**
 * User books list shortcode
 * Usage: [user_books_list type="wishlist" post_type="livre" limit="10" show_thumbnails="true"]
 */
function user_books_list_shortcode($atts)
{
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return '<p>' . __('Vous devez être connecté pour voir vos livres.', 'bdcomic_theme') . '</p>';
    }

    $atts = shortcode_atts(array(
        'type' => 'wishlist',
        'post_type' => 'livre',
        'limit' => 10,
        'show_thumbnails' => 'true',
        'show_actions' => 'true'
    ), $atts);

    $current_user_id = get_current_user_id();
    $list_type = sanitize_text_field($atts['type']);
    $post_type = sanitize_text_field($atts['post_type']);
    $limit = intval($atts['limit']);
    $show_thumbnails = $atts['show_thumbnails'] === 'true';
    $show_actions = $atts['show_actions'] === 'true';

    // Validate list type
    if (!in_array($list_type, ['wishlist', 'read', 'owned', 'collection_wishlist', 'loaned'])) {
        return '<p>' . __('Type de liste invalide.', 'bdcomic_theme') . '</p>';
    }

    $books = get_user_books($current_user_id, $list_type, $post_type);

    if (empty($books)) {
        $list_labels = get_list_type_labels();
        return '<p>' . sprintf(__('Aucun %s trouvé.', 'bdcomic_theme'), strtolower($list_labels[$list_type])) . '</p>';
    }

    // Limit results
    if ($limit > 0) {
        $books = array_slice($books, 0, $limit);
    }

    $list_labels = get_list_type_labels();
    $output = '<div class="user-books-shortcode-list">';
    $output .= '<h3>' . esc_html($list_labels[$list_type]) . '</h3>';
    $output .= '<ul class="user-books-list">';

    foreach ($books as $book_data) {
        $book = $book_data['post'];
        $output .= '<li class="user-books-list-item">';

        $output .= '<div class="user-books-list-item-info">';

        if ($show_thumbnails) {
            if ($post_type === 'livre') {
                $photo_devant = get_field('photo_devant', $book->ID);
                if ($photo_devant) {
                    $output .= '<img src="' . esc_url($photo_devant['sizes']['thumbnail']) . '" alt="' . esc_attr($photo_devant['alt']) . '" class="book-thumbnail">';
                }
            } else {
                $logo = get_field('logo_collection', $book->ID);
                if ($logo) {
                    $output .= '<img src="' . esc_url($logo['sizes']['thumbnail']) . '" alt="' . esc_attr($logo['alt']) . '" class="book-thumbnail">';
                }
            }
        }

        $output .= '<div class="book-details">';

        $title = '';
        if ($post_type === 'livre') {
            $titre = get_field('titre_livre', $book->ID);
            $title = $titre ? $titre : $book->post_title;
        } else {
            $nom = get_field('nom_collection', $book->ID);
            $title = $nom ? $nom : $book->post_title;
        }

        $output .= '<h4 class="user-books-list-item-title">';
        $output .= '<a href="' . get_permalink($book->ID) . '">' . esc_html($title) . '</a>';
        $output .= '</h4>';

        $output .= '<div class="user-books-list-item-meta">';
        $output .= 'Ajouté le ' . date_i18n(get_option('date_format'), strtotime($book_data['added_date']));
        $output .= '</div>';

        $output .= '</div>';
        $output .= '</div>';

        if ($show_actions) {
            $output .= '<div class="user-books-list-item-actions">';
            $output .= '<button class="book-action-btn active" ';
            $output .= 'data-post-id="' . $book->ID . '" ';
            $output .= 'data-list-type="' . $list_type . '" ';
            $output .= 'data-action="remove" ';
            $output .= 'data-post-type="' . $post_type . '">';
            $output .= '<span class="btn-icon dashicons ';

            switch ($list_type) {
                case 'wishlist':
                    $output .= 'dashicons-heart-filled';
                    break;
                case 'read':
                    $output .= 'dashicons-yes';
                    break;
                case 'owned':
                    $output .= 'dashicons-archive';
                    break;
                case 'collection_wishlist':
                    $output .= 'dashicons-star-filled';
                    break;

            }

            $output .= '"></span>';
            $output .= '<span class="btn-text">' . __('Retirer', 'bdcomic_theme') . '</span>';
            $output .= '</button>';
            $output .= '</div>';
        }

        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>';

    return $output;
}

/**
 * AJAX handler to report a book problem
 */
function report_book_problem_ajax()
{
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'report_book_problem_nonce')) {
        wp_send_json_error(array('message' => __('Échec de la vérification de sécurité', 'bdcomic_theme')));
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Vous devez être connecté pour signaler un problème', 'bdcomic_theme')));
    }

    $post_id = intval($_POST['post_id']);
    $message = sanitize_textarea_field($_POST['message']);
    $user_id = get_current_user_id();

    // Validate post exists
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'livre') {
        wp_send_json_error(array('message' => __('Livre introuvable', 'bdcomic_theme')));
    }

    // Validate message
    if (empty($message)) {
        wp_send_json_error(array('message' => __('Veuillez décrire le problème', 'bdcomic_theme')));
    }

    // Get existing reports
    $reports = get_field('livre_problem_reports', $post_id);
    if (!is_array($reports)) {
        $reports = array();
    }

    // Add new report - ACF will automatically map field names to field keys
    $new_report = array(
        'user_id' => $user_id,
        'message' => $message,
        'date' => current_time('Y-m-d H:i:s'),
        'status' => 'pending'
    );

    $reports[] = $new_report;

    // Save reports - ACF will handle the field keys automatically
    update_field('livre_problem_reports', $reports, $post_id);

    // Send email notification to moderators
    $moderators = get_users(array('role__in' => array('administrator', 'editor')));
    if (!empty($moderators)) {
        $book_title = get_field('titre_livre', $post_id) ?: get_the_title($post_id);
        $user = get_userdata($user_id);
        $subject = sprintf(__('[%s] Signalement de problème - %s', 'bdcomic_theme'), get_bloginfo('name'), $book_title);
        $email_message = sprintf(
            __("Un utilisateur a signalé un problème concernant le livre suivant:\n\nLivre: %s\nLien: %s\n\nUtilisateur: %s (%s)\n\nMessage:\n%s\n\n", 'bdcomic_theme'),
            $book_title,
            get_permalink($post_id),
            $user->display_name,
            $user->user_email,
            $message
        );

        foreach ($moderators as $moderator) {
            wp_mail($moderator->user_email, $subject, $email_message);
        }
    }

    wp_send_json_success(array('message' => __('Problème signalé avec succès. Merci de votre contribution.', 'bdcomic_theme')));
}
