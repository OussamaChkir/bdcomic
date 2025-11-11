<?php
/**
 * Template part for displaying a livre in grid format
 * Used specifically in the book grid component
 * 
 * @package bdcomic_theme
 */

// Get the post ID - prioritize global variable from component
$post_id = null;

// First try to get from global variable set by the component
if (isset($GLOBALS['current_book_id']) && $GLOBALS['current_book_id']) {
    $post_id = $GLOBALS['current_book_id'];
}

// Fallback to global post if available
if (!$post_id && isset($post) && $post && isset($post->ID)) {
    $post_id = $post->ID;
}

// Fallback to get_the_ID() if global post is not available
if (!$post_id) {
    $post_id = get_the_ID();
}

// If still no post ID, return early
if (!$post_id) {
    return;
}

// Get ACF fields using the specific post ID
$photo_devant = get_field('photo_devant', $post_id);
$photo_derriere = get_field('photo_derriere', $post_id);
$titre = get_field('titre_livre', $post_id);
$variante = get_field('variante', $post_id);
$maison_edition = get_field('maison_d\'edition', $post_id);
$collection = get_field('collection', $post_id);
$tirage_limite = get_field('tirage_limite', $post_id);
$n_sortie = get_field('n_sortie', $post_id);
$n_frise = get_field('n_frise', $post_id);
$date_sortie = get_field('date_sortie_livre', $post_id);
$nombre_pages = get_field('nombre_de_pages', $post_id);
$resume = get_field('resume_livre', $post_id);
$equipe_creative = get_field('equipe_creative', $post_id);
$isbnean13 = get_field('isbnean13', $post_id);

// Get post title as fallback
$post_title = get_the_title($post_id);
$book_title = $titre ? $titre : $post_title;

// Check if user is logged in and get book status
$is_loaned = false;
$has_problem = false;
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id();
    $is_loaned = is_book_in_user_list($current_user_id, $post_id, 'loaned');
    
    // Check if book has pending problem reports
    $problem_reports = get_field('livre_problem_reports', $post_id);
    if (is_array($problem_reports) && !empty($problem_reports)) {
        foreach ($problem_reports as $report) {
            if (isset($report['status']) && $report['status'] === 'pending') {
                $has_problem = true;
                break;
            }
        }
    }
}
?>

<article id="post-<?php echo $post_id; ?>" class="livre-item">
    <div class="livre-content livres-grid">
        <div class="livre-covers">
            <div class="cover-front">
                <?php if ($photo_devant) : ?>
                    <img src="<?php echo esc_url($photo_devant['url']); ?>" 
                         alt="<?php echo esc_attr($photo_devant['alt']); ?>" 
                         class="livre-cover">
                <?php else : ?>
                    <div class="no-image-placeholder">
                        <span class="dashicons dashicons-book"></span>
                    </div>
                <?php endif; ?>
                
                <?php if (is_user_logged_in()) : ?>
                <div class="livre-status-icons">
                    <?php if ($is_loaned) : ?>
                        <span class="status-icon loaned" title="<?php _e('Prêté', 'bdcomic_theme'); ?>">
                            <span class="dashicons dashicons-share"></span>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($has_problem) : ?>
                        <span class="status-icon problem-reported" title="<?php _e('Problème signalé', 'bdcomic_theme'); ?>">
                            <span class="dashicons dashicons-warning"></span>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="livre-action-icons">
                    <button class="book-action-icon report-problem-btn" 
                            data-book-id="<?php echo $post_id; ?>"
                            title="<?php _e('Signaler un problème', 'bdcomic_theme'); ?>"
                            aria-label="<?php _e('Signaler un problème', 'bdcomic_theme'); ?>">
                        <span class="dashicons dashicons-flag"></span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="livre-details">
            <h2 class="livre-title">
                <a href="<?php echo get_permalink($post_id); ?>">
                    <?php echo esc_html($book_title); ?>
                </a>
            </h2>

            <?php if ($variante) : ?>
                <div class="livre-variant">
                    <span class="variant-badge">Variante</span>
                </div>
            <?php endif; ?>

            <?php if ($maison_edition) : ?>
                <div class="livre-publisher">
                    <strong>Éditeur:</strong> 
                    <a href="<?php echo get_permalink($maison_edition->ID); ?>">
                        <?php echo esc_html($maison_edition->post_title); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($collection) : ?>
                <div class="livre-collection">
                    <strong>Collection:</strong> 
                    <a href="<?php echo get_permalink($collection->ID); ?>">
                        <?php echo esc_html($collection->post_title); ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="livre-meta">
                <?php if ($date_sortie) : ?>
                    <span class="meta-item">
                        <strong>Date:</strong> <?php echo esc_html($date_sortie); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($n_sortie) : ?>
                    <span class="meta-item">
                        <strong>N°:</strong> <?php echo esc_html($n_sortie); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($nombre_pages) : ?>
                    <span class="meta-item">
                        <strong>Pages:</strong> <?php echo esc_html($nombre_pages); ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($tirage_limite) : ?>
                <div class="livre-limited">
                    <span class="limited-badge">Tirage limité</span>
                </div>
            <?php endif; ?>

            <?php if ($resume) : ?>
                <div class="livre-summary">
                    <?php echo wp_trim_words($resume, 25, '...'); ?>
                </div>
            <?php endif; ?>

            <?php if ($isbnean13) : ?>
                <div class="livre-isbn">
                    <strong>ISBN:</strong> <?php echo esc_html($isbnean13); ?>
                </div>
            <?php endif; ?>

            <div class="livre-actions">
                <a href="<?php echo get_permalink($post_id); ?>" class="read-more">
                    Voir le livre
                </a>
                
                <?php if (is_user_logged_in()) : ?>
                    <div class="book-quick-actions">
                        <?php
                        $current_user_id = get_current_user_id();
                        
                        // Quick wishlist button
                        $in_wishlist = is_book_in_user_list($current_user_id, $post_id, 'wishlist');
                        ?>
                        <button class="book-quick-action <?php echo $in_wishlist ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="wishlist"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $in_wishlist ? __('Retirer des souhaits', 'bdcomic_theme') : __('Ajouter aux souhaits', 'bdcomic_theme'); ?>">
                                <i class="bi <?php echo $in_wishlist ? 'bi-heart-fill' : 'bi-heart'; ?>"></i>
                        </button>
                        
                        <?php
                        // Quick read button
                        $is_read = is_book_in_user_list($current_user_id, $post_id, 'read');
                        ?>
                        <button class="book-quick-action <?php echo $is_read ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="read"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $is_read ? __('Marquer comme non lu', 'bdcomic_theme') : __('Marquer comme lu', 'bdcomic_theme'); ?>">
                            <i class="bi <?php echo $is_read ? 'bi-check-lg' : 'bi-check-circle-fill'; ?>"></i>
                        </button>
						
						<?php
						// Quick owned button
						$is_owned = is_book_in_user_list($current_user_id, $post_id, 'owned');
						?>
						<button class="book-quick-action <?php echo $is_owned ? 'active' : ''; ?>" 
								data-post-id="<?php echo $post_id; ?>" 
								data-list-type="owned"
								data-post-type="livre"
								data-bs-toggle="tooltip" 
								title="<?php echo $is_owned ? __('Marquer comme non possédé', 'bdcomic_theme') : __('Marquer comme possédé', 'bdcomic_theme'); ?>">
							<i class="bi <?php echo $is_owned ? 'bi-bag-check-fill' : 'bi-bag-check'; ?>"></i>
						</button>
                        
                        <?php
                        // Quick loaned button
                        ?>
                        <button class="book-quick-action <?php echo $is_loaned ? 'active' : ''; ?>" 
                                data-post-id="<?php echo $post_id; ?>" 
                                data-list-type="loaned"
                                data-post-type="livre"
                                data-bs-toggle="tooltip" 
                                title="<?php echo $is_loaned ? __('Marquer comme non prêté', 'bdcomic_theme') : __('Marquer comme prêté', 'bdcomic_theme'); ?>">
                            <span class="dashicons dashicons-share"></span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
