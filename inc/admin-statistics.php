<?php
/**
 * Admin Statistics Page
 * 
 * @package bdcomic_theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the statistics menu page
 */
function bdcomic_register_statistics_page()
{
    add_menu_page(
        __('Statistiques', 'bdcomic_theme'),
        __('Statistiques', 'bdcomic_theme'),
        'edit_others_posts', // Capability required (Editor & Admin)
        'bdcomic-statistics',
        'bdcomic_render_statistics_page',
        'dashicons-chart-bar',
        31 // Position below "Signalements livres"
    );
}
add_action('admin_menu', 'bdcomic_register_statistics_page');

/**
 * Render the statistics page
 */
function bdcomic_render_statistics_page()
{
    if (!current_user_can('edit_others_posts')) {
        wp_die(__('Vous n’avez pas les permissions nécessaires.', 'bdcomic_theme'));
    }

    // Gather Statistics
    $user_count = count_users();
    $total_users = $user_count['total_users'];

    $count_livre = wp_count_posts('livre');
    $total_livres = $count_livre->publish;

    $count_collection = wp_count_posts('collection');
    $total_collections = $count_collection->publish;

    $count_sous_collection = wp_count_posts('sous_collection');
    $total_sous_collections = $count_sous_collection->publish;

    $count_guide = wp_count_posts('guide_lecture');
    $total_guides = $count_guide->publish;

    // Custom Table Stats
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_books';

    // Helper to get count by list type
    function get_user_book_count($list_type, $post_type = 'livre')
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_books';
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE list_type = %s AND post_type = %s",
            $list_type,
            $post_type
        ));
    }

    $total_owned = get_user_book_count('owned');
    $total_read = get_user_book_count('read');
    $total_wishlist = get_user_book_count('wishlist');
    $total_loaned = get_user_book_count('loaned');
    $total_collection_wishlist = get_user_book_count('collection_wishlist', 'collection');

    // Helper for Top Lists
    function get_top_books($list_type, $limit = 10)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_books';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT post_id, COUNT(DISTINCT user_id) as count 
            FROM $table_name 
            WHERE list_type = %s 
            GROUP BY post_id 
            ORDER BY count DESC 
            LIMIT %d",
            $list_type,
            $limit
        ));

        $books = array();
        foreach ($results as $row) {
            $books[] = array(
                'title' => get_the_title($row->post_id),
                'link' => get_edit_post_link($row->post_id),
                'count' => $row->count,
                'id' => $row->post_id
            );
        }
        return $books;
    }

    $top_wishlist = get_top_books('wishlist');
    $top_owned = get_top_books('owned');
    $top_read = get_top_books('read');

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Tableau de bord Statistiques', 'bdcomic_theme'); ?></h1>

        <div class="bdcomic-stats-grid">
            <!-- General Content Stats -->
            <div class="card">
                <h2><?php esc_html_e('Contenu du Site', 'bdcomic_theme'); ?></h2>
                <ul class="stats-list">
                    <li><strong><?php esc_html_e('Utilisateurs inscrits:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_users); ?></li>
                    <li><strong><?php esc_html_e('Livres:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_livres); ?></li>
                    <li><strong><?php esc_html_e('Collections:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_collections); ?></li>
                    <li><strong><?php esc_html_e('Sous-collections:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_sous_collections); ?></li>
                    <li><strong><?php esc_html_e('Guides de lecture:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_guides); ?></li>
                </ul>
            </div>

            <!-- User Interaction Stats -->
            <div class="card">
                <h2><?php esc_html_e('Interactions Utilisateurs', 'bdcomic_theme'); ?></h2>
                <ul class="stats-list">
                    <li><strong><?php esc_html_e('Livres possédés (total):', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_owned); ?></li>
                    <li><strong><?php esc_html_e('Livres lus (total):', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_read); ?></li>
                    <li><strong><?php esc_html_e('Livres dans les souhaits:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_wishlist); ?></li>
                    <li><strong><?php esc_html_e('Livres prêtés:', 'bdcomic_theme'); ?></strong>
                        <?php echo number_format_i18n($total_loaned); ?></li>
                </ul>
            </div>
        </div>

        <br class="clear">

        <h2><?php esc_html_e('Palmarès des Livres', 'bdcomic_theme'); ?></h2>

        <div class="bdcomic-stats-tables">

            <div class="stats-table-container">
                <h3><?php esc_html_e('Top souhaits', 'bdcomic_theme'); ?></h3>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Livre', 'bdcomic_theme'); ?></th>
                            <th><?php esc_html_e('Nombre', 'bdcomic_theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_wishlist)): ?>
                            <tr>
                                <td colspan="2"><?php esc_html_e('Aucune donnée', 'bdcomic_theme'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($top_wishlist as $book): ?>
                                <tr>
                                    <td><a href="<?php echo esc_url($book['link']); ?>"><?php echo esc_html($book['title']); ?></a>
                                    </td>
                                    <td><?php echo intval($book['count']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="stats-table-container">
                <h3><?php esc_html_e('Top possédés', 'bdcomic_theme'); ?></h3>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Livre', 'bdcomic_theme'); ?></th>
                            <th><?php esc_html_e('Nombre', 'bdcomic_theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_owned)): ?>
                            <tr>
                                <td colspan="2"><?php esc_html_e('Aucune donnée', 'bdcomic_theme'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($top_owned as $book): ?>
                                <tr>
                                    <td><a href="<?php echo esc_url($book['link']); ?>"><?php echo esc_html($book['title']); ?></a>
                                    </td>
                                    <td><?php echo intval($book['count']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="stats-table-container">
                <h3><?php esc_html_e('Top lus', 'bdcomic_theme'); ?></h3>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Livre', 'bdcomic_theme'); ?></th>
                            <th><?php esc_html_e('Nombre', 'bdcomic_theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_read)): ?>
                            <tr>
                                <td colspan="2"><?php esc_html_e('Aucune donnée', 'bdcomic_theme'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($top_read as $book): ?>
                                <tr>
                                    <td><a href="<?php echo esc_url($book['link']); ?>"><?php echo esc_html($book['title']); ?></a>
                                    </td>
                                    <td><?php echo intval($book['count']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <style>
        .bdcomic-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .bdcomic-stats-grid .card {
            max-width: none;
            margin: 0;
            padding: 20px;
        }

        .stats-list {
            margin: 0;
            font-size: 14px;
        }

        .stats-list li {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            justify-content: space-between;
        }

        .stats-list li:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .bdcomic-stats-tables {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stats-table-container h3 {
            margin-top: 0;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }
    </style>
    <?php
}
