<?php
/**
 * Admin page to manage reported book problems.
 *
 * @package bdcomic_theme
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'bdcomic_register_book_problem_page');
add_action('admin_post_bdcomic_resolve_book_problem', 'bdcomic_resolve_book_problem');

/**
 * Register the backoffice page for problem reports.
 */
function bdcomic_register_book_problem_page() {
    add_menu_page(
        __('Signalements livres', 'bdcomic_theme'),
        __('Signalements livres', 'bdcomic_theme'),
        'edit_posts',
        'bdcomic-book-problems',
        'bdcomic_render_book_problem_page',
        'dashicons-warning',
        30
    );
}

/**
 * Render the admin page content.
 */
function bdcomic_render_book_problem_page() {
    if (!current_user_can('edit_posts')) {
        wp_die(__('Vous n’avez pas les permissions nécessaires.', 'bdcomic_theme'));
    }

    $per_page = 20;
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

    $query = new WP_Query(array(
        'post_type'      => 'livre',
        'post_status'    => 'any',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'meta_query'     => array(
            array(
                'key'     => 'livre_problem_reports',
                'value'   => 'pending',
                'compare' => 'LIKE',
            ),
        ),
    ));

    $reports_rows = bdcomic_collect_problem_rows($query->posts);
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Signalements de livres', 'bdcomic_theme'); ?></h1>

        <?php if (!empty($_GET['bdcomic_notice'])) : ?>
            <?php bdcomic_render_problem_notices(sanitize_text_field($_GET['bdcomic_notice'])); ?>
        <?php endif; ?>

        <?php if (empty($reports_rows)) : ?>
            <p><?php esc_html_e('Aucun signalement en attente.', 'bdcomic_theme'); ?></p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Livre', 'bdcomic_theme'); ?></th>
                        <th><?php esc_html_e('Utilisateur', 'bdcomic_theme'); ?></th>
                        <th><?php esc_html_e('Message', 'bdcomic_theme'); ?></th>
                        <th><?php esc_html_e('Date', 'bdcomic_theme'); ?></th>
                        <th><?php esc_html_e('Actions', 'bdcomic_theme'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports_rows as $row) : ?>
                        <tr>
                            <td>
                                <strong><a href="<?php echo esc_url(get_permalink($row['post_id'])); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($row['book_title']); ?>
                                </a></strong>
                                <div class="row-actions">
                                    <span class="edit"><a href="<?php echo esc_url(get_edit_post_link($row['post_id'])); ?>">
                                        <?php esc_html_e('Modifier', 'bdcomic_theme'); ?>
                                    </a></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($row['user_name']) : ?>
                                    <?php echo esc_html($row['user_name']); ?>
                                    <br>
                                    <a href="mailto:<?php echo esc_attr($row['user_email']); ?>">
                                        <?php echo esc_html($row['user_email']); ?>
                                    </a>
                                <?php else : ?>
                                    <em><?php esc_html_e('Utilisateur supprimé', 'bdcomic_theme'); ?></em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo nl2br(esc_html($row['message'])); ?>
                            </td>
                            <td>
                                <?php echo esc_html(bdcomic_format_report_date($row['date'])); ?>
                            </td>
                            <td>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                                    <?php wp_nonce_field('bdcomic_resolve_book_problem'); ?>
                                    <input type="hidden" name="action" value="bdcomic_resolve_book_problem">
                                    <input type="hidden" name="post_id" value="<?php echo esc_attr($row['post_id']); ?>">
                                    <input type="hidden" name="report_index" value="<?php echo esc_attr($row['report_index']); ?>">
                                    <input type="hidden" name="new_status" value="resolved">
                                    <button type="submit" class="button button-primary">
                                        <?php esc_html_e('Marquer comme résolu', 'bdcomic_theme'); ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            $total_posts = $query->found_posts;
            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
                echo '<div class="tablenav"><div class="tablenav-pages">';
                echo paginate_links(array(
                    'base'      => add_query_arg(array('paged' => '%#%')),
                    'format'    => '',
                    'prev_text' => __('&laquo;', 'bdcomic_theme'),
                    'next_text' => __('&raquo;', 'bdcomic_theme'),
                    'total'     => $total_pages,
                    'current'   => $paged,
                ));
                echo '</div></div>';
            }
            ?>
        <?php endif; ?>
    </div>
    <?php
    wp_reset_postdata();
}

/**
 * Collect table rows for each pending report.
 *
 * @param WP_Post[] $posts Posts returned by the query.
 *
 * @return array
 */
function bdcomic_collect_problem_rows($posts) {
    $rows = array();

    foreach ($posts as $post) {
        $reports = get_field('livre_problem_reports', $post->ID);
        if (!is_array($reports)) {
            continue;
        }

        foreach ($reports as $index => $report) {
            if (empty($report['status']) || 'pending' !== $report['status']) {
                continue;
            }

            $user = !empty($report['user_id']) ? get_userdata($report['user_id']) : null;

            $rows[] = array(
                'post_id'      => $post->ID,
                'report_index' => $index,
                'book_title'   => get_field('titre_livre', $post->ID) ?: get_the_title($post->ID),
                'user_name'    => $user ? $user->display_name : '',
                'user_email'   => $user ? $user->user_email : '',
                'message'      => isset($report['message']) ? $report['message'] : '',
                'date'         => isset($report['date']) ? $report['date'] : '',
            );
        }
    }

    return $rows;
}

/**
 * Format report date for display.
 *
 * @param string $date Raw date string.
 *
 * @return string
 */
function bdcomic_format_report_date($date) {
    if (empty($date)) {
        return '';
    }

    $timestamp = strtotime($date);
    if (!$timestamp) {
        return esc_html($date);
    }

    return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
}

/**
 * Handle report resolution.
 */
function bdcomic_resolve_book_problem() {
    if (!current_user_can('edit_posts')) {
        wp_die(__('Vous n’avez pas les permissions nécessaires.', 'bdcomic_theme'));
    }

    check_admin_referer('bdcomic_resolve_book_problem');

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $report_index = isset($_POST['report_index']) ? intval($_POST['report_index']) : -1;

    if (!$post_id || $report_index < 0) {
        wp_safe_redirect(add_query_arg(array(
            'page'            => 'bdcomic-book-problems',
            'bdcomic_notice'  => 'error',
        ), admin_url('admin.php')));
        exit;
    }

    $reports = get_field('livre_problem_reports', $post_id);

    if (!is_array($reports) || !isset($reports[$report_index])) {
        wp_safe_redirect(add_query_arg(array(
            'page'            => 'bdcomic-book-problems',
            'bdcomic_notice'  => 'missing',
        ), admin_url('admin.php')));
        exit;
    }

    $new_status = isset($_POST['new_status']) ? sanitize_text_field($_POST['new_status']) : 'resolved';

    $reports[$report_index]['status'] = $new_status;
    update_field('livre_problem_reports', $reports, $post_id);

    wp_safe_redirect(add_query_arg(array(
        'page'           => 'bdcomic-book-problems',
        'bdcomic_notice' => 'success',
    ), admin_url('admin.php')));
    exit;
}

/**
 * Render contextual admin notices.
 *
 * @param string $notice_key Notice slug.
 */
function bdcomic_render_problem_notices($notice_key) {
    $messages = array(
        'success' => __('Signalement marqué comme résolu.', 'bdcomic_theme'),
        'missing' => __('Signalement introuvable. Il a peut-être déjà été supprimé.', 'bdcomic_theme'),
        'error'   => __('Une erreur s’est produite. Veuillez réessayer.', 'bdcomic_theme'),
    );

    if (!isset($messages[$notice_key])) {
        return;
    }

    $classes = 'notice notice-info';
    if ('success' === $notice_key) {
        $classes = 'notice notice-success';
    } elseif ('error' === $notice_key) {
        $classes = 'notice notice-error';
    }

    printf(
        '<div class="%1$s"><p>%2$s</p></div>',
        esc_attr($classes),
        esc_html($messages[$notice_key])
    );
}

