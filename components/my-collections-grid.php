<?php
/**
 * My Collections Grid Component
 * Displays user's comic collection with search, filtering, and statistics
 * 
 * @package bdcomic_theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get ACF fields
$collections_title = get_field('collections_title') ?: __('Mes Collections', 'bdcomic_theme');
$collections_description = get_field('collections_description');
$show_statistics = get_field('show_statistics') !== false ? get_field('show_statistics') : true;
$show_search = get_field('show_search') !== false ? get_field('show_search') : true;
$books_per_row = get_field('books_per_row') ?: '4';
$show_collection_logos = get_field('show_collection_logos') !== false ? get_field('show_collection_logos') : true;
$show_status_icons = get_field('show_status_icons') !== false ? get_field('show_status_icons') : true;

// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="collections-grid-login-required">';
    echo '<p>' . __('Vous devez être connecté pour voir vos collections.', 'bdcomic_theme') . '</p>';
    echo '</div>';
    return;
}

$current_user_id = get_current_user_id();

// Ensure the database table exists
create_user_books_tables();

// Get user's owned books (from wishlist and read lists combined)
$wishlist_books = get_user_books($current_user_id, 'wishlist', 'livre');
$read_books_data = get_user_books($current_user_id, 'read', 'livre');

$owned_books = array_merge($wishlist_books, $read_books_data);

// Remove duplicates by post ID
$unique_books = array();
$seen_ids = array();
foreach ($owned_books as $book_data) {
    $book_id = $book_data['post']->ID;
    if (!in_array($book_id, $seen_ids)) {
        $unique_books[] = $book_data;
        $seen_ids[] = $book_id;
    }
}
$owned_books = $unique_books;

// Get user's read books (already retrieved above)
$read_books = $read_books_data;
$read_book_ids = array_column($read_books, 'post');
$read_book_ids = array_column($read_book_ids, 'ID');

// Get user's loaned books (we'll need to add this functionality)
// For now, we'll use a placeholder
$loaned_books = array(); // TODO: Implement loaned books functionality

// Calculate statistics
$total_owned = count($owned_books);
$total_read = count($read_books);
$total_loaned = count($loaned_books);
$total_unread = $total_owned - $total_read;

// Group books by collection/subcollection
$collections_data = array();
foreach ($owned_books as $book_data) {
    $book = $book_data['post'];
    $collection = get_field('collection', $book->ID);
    
    if ($collection) {
        $collection_id = $collection->ID;
        $collection_name = get_field('nom_collection', $collection_id) ?: $collection->post_title;
        
        // Get sous-collection field
        $sous_collection = get_field('sous_collection', $book->ID);
        
        $group_key = $sous_collection ? $sous_collection : $collection_name;
        
        if (!isset($collections_data[$group_key])) {
            $collections_data[$group_key] = array(
                'name' => $group_key,
                'is_sous_collection' => !empty($sous_collection),
                'collection_id' => $collection_id,
                'books' => array(),
                'total_books' => 0,
                'read_books' => 0,
                'loaned_books' => 0
            );
        }
        
        $collections_data[$group_key]['books'][] = $book;
        $collections_data[$group_key]['total_books']++;
        
        if (in_array($book->ID, $read_book_ids)) {
            $collections_data[$group_key]['read_books']++;
        }
        
        // TODO: Check if book is loaned
        if (in_array($book->ID, array_column($loaned_books, 'ID'))) {
            $collections_data[$group_key]['loaned_books']++;
        }
    }
}

// Sort collections by name
ksort($collections_data);
?>

<div class="my-collections-grid" data-user-id="<?php echo $current_user_id; ?>" data-books-per-row="<?php echo esc_attr($books_per_row); ?>">
    <!-- Title and Description -->
    <div class="collections-header">
        <h2 class="collections-title"><?php echo esc_html($collections_title); ?></h2>
        <?php if ($collections_description) : ?>
            <div class="collections-description">
                <?php echo wp_kses_post($collections_description); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($show_search) : ?>
    <!-- Search and Filter Section -->
    <div class="collections-search-section">
        <div class="search-filters">
            <div class="search-input-group">
                <input type="text" 
                       id="collections-search" 
                       class="search-input" 
                       placeholder="<?php _e('Rechercher par mots-clés...', 'bdcomic_theme'); ?>"
                       data-search-type="keywords">
                <button type="button" class="search-clear" title="<?php _e('Effacer la recherche', 'bdcomic_theme'); ?>">
                    <span class="dashicons dashicons-dismiss"></span>
                </button>
            </div>
            
            <div class="filter-selects">
                <select id="filter-maison" class="filter-select" data-filter-type="maison">
                    <option value=""><?php _e('Toutes les maisons', 'bdcomic_theme'); ?></option>
                    <?php
                    // Get all unique publishers from owned books
                    $publishers = array();
                    foreach ($owned_books as $book_data) {
                        $book = $book_data['post'];
                        $maison_edition = get_field('maison_d\'edition', $book->ID);
                        if ($maison_edition && !in_array($maison_edition->ID, array_column($publishers, 'id'))) {
                            $publishers[] = array(
                                'id' => $maison_edition->ID,
                                'name' => $maison_edition->post_title
                            );
                        }
                    }
                    foreach ($publishers as $publisher) {
                        echo '<option value="' . $publisher['id'] . '">' . esc_html($publisher['name']) . '</option>';
                    }
                    ?>
                </select>
                
                <select id="filter-collection" class="filter-select" data-filter-type="collection">
                    <option value=""><?php _e('Toutes les collections', 'bdcomic_theme'); ?></option>
                    <?php
                    foreach ($collections_data as $collection_key => $collection_data) {
                        echo '<option value="' . $collection_data['collection_id'] . '">' . esc_html($collection_data['name']) . '</option>';
                    }
                    ?>
                </select>
                
                <select id="filter-artiste" class="filter-select" data-filter-type="artiste">
                    <option value=""><?php _e('Tous les artistes', 'bdcomic_theme'); ?></option>
                    <?php
                    // Get all unique artists from owned books
                    $artists = array();
                    foreach ($owned_books as $book_data) {
                        $book = $book_data['post'];
                        $equipe_creative = get_field('equipe_creative', $book->ID);
                        if ($equipe_creative) {
                            foreach ($equipe_creative as $member) {
                                if (isset($member['artiste']) && $member['artiste']) {
                                    $artist_id = $member['artiste']->ID;
                                    if (!in_array($artist_id, array_column($artists, 'id'))) {
                                        $artists[] = array(
                                            'id' => $artist_id,
                                            'name' => $member['artiste']->post_title
                                        );
                                    }
                                }
                            }
                        }
                    }
                    foreach ($artists as $artist) {
                        echo '<option value="' . $artist['id'] . '">' . esc_html($artist['name']) . '</option>';
                    }
                    ?>
                </select>
                
                <input type="date" 
                       id="filter-date" 
                       class="filter-date" 
                       data-filter-type="date"
                       placeholder="<?php _e('Date de parution', 'bdcomic_theme'); ?>">
            </div>
        </div>
        
        <div class="view-filters">
            <div class="filter-buttons">
                <button type="button" class="filter-btn active" data-filter="all">
                    <?php _e('Tous', 'bdcomic_theme'); ?>
                </button>
                <button type="button" class="filter-btn" data-filter="unread">
                    <?php _e('Non lus', 'bdcomic_theme'); ?>
                </button>
                <button type="button" class="filter-btn" data-filter="loaned">
                    <?php _e('Prêtés', 'bdcomic_theme'); ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($show_statistics) : ?>
    <!-- Statistics Section -->
    <div class="collections-statistics">
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_owned; ?></div>
            <div class="stat-label"><?php _e('Comics détenus', 'bdcomic_theme'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_unread; ?></div>
            <div class="stat-label"><?php _e('Non lus', 'bdcomic_theme'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_loaned; ?></div>
            <div class="stat-label"><?php _e('Prêtés', 'bdcomic_theme'); ?></div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Collections List -->
    <div class="collections-list">
        <?php if (empty($collections_data)) : ?>
            <div class="collections-empty">
                <div class="empty-icon">
                    <span class="dashicons dashicons-book"></span>
                </div>
                <h3><?php _e('Aucune collection trouvée', 'bdcomic_theme'); ?></h3>
                <p><?php _e('Commencez par ajouter des livres à vos souhaits ou marquez-les comme lus.', 'bdcomic_theme'); ?></p>
            </div>
        <?php else : ?>
            <?php foreach ($collections_data as $collection_key => $collection_data) : ?>
                <?php
                $collection_id = $collection_data['collection_id'];
                $collection_status = get_field('etat_collection', $collection_id);
                $collection_logo = get_field('logo_collection', $collection_id);
                ?>
                
                <div class="collection-group" data-collection-id="<?php echo $collection_id; ?>">
                    <div class="collection-header">
                        <div class="collection-info">
                            <?php if ($show_collection_logos && $collection_logo) : ?>
                                <div class="collection-logo">
                                    <img src="<?php echo esc_url($collection_logo['sizes']['thumbnail']); ?>" 
                                         alt="<?php echo esc_attr($collection_logo['alt']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="collection-details">
                                <h3 class="collection-name">
                                    <a href="<?php echo get_permalink($collection_id); ?>">
                                        <?php echo esc_html($collection_data['name']); ?>
                                    </a>
                                </h3>
                                
                                <div class="collection-stats">
                                    <span class="stat-item">
                                        <strong><?php echo $collection_data['total_books']; ?></strong> 
                                        <?php _e('albums', 'bdcomic_theme'); ?>
                                    </span>
                                    <span class="stat-item">
                                        <strong><?php echo $collection_data['read_books']; ?></strong> 
                                        <?php _e('lus', 'bdcomic_theme'); ?>
                                    </span>
                                    <?php if ($collection_data['loaned_books'] > 0) : ?>
                                        <span class="stat-item loaned">
                                            <strong><?php echo $collection_data['loaned_books']; ?></strong> 
                                            <?php _e('prêtés', 'bdcomic_theme'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="collection-status">
                            <?php if ($collection_status) : ?>
                                <span class="status-badge status-<?php echo sanitize_html_class(strtolower($collection_status)); ?>">
                                    <?php
                                    $status_icons = array(
                                        'En cours' => 'dashicons-controls-play',
                                        'Terminée' => 'dashicons-yes-alt',
                                        'Abandonné' => 'dashicons-dismiss'
                                    );
                                    $icon = isset($status_icons[$collection_status]) ? $status_icons[$collection_status] : 'dashicons-info';
                                    ?>
                                    <span class="dashicons <?php echo $icon; ?>"></span>
                                    <?php echo esc_html($collection_status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="collection-books">
                        <?php foreach ($collection_data['books'] as $book) : ?>
                            <?php
                            $is_read = in_array($book->ID, $read_book_ids);
                            $is_loaned = in_array($book->ID, array_column($loaned_books, 'ID'));
                            $book_classes = array('book-item');
                            if ($is_read) $book_classes[] = 'book-read';
                            if ($is_loaned) $book_classes[] = 'book-loaned';
                            
                            $photo_devant = get_field('photo_devant', $book->ID);
                            $titre = get_field('titre_livre', $book->ID) ?: $book->post_title;
                            $n_sortie = get_field('n_sortie', $book->ID);
                            ?>
                            
                            <div class="<?php echo implode(' ', $book_classes); ?>" 
                                 data-book-id="<?php echo $book->ID; ?>"
                                 data-read="<?php echo $is_read ? '1' : '0'; ?>"
                                 data-loaned="<?php echo $is_loaned ? '1' : '0'; ?>">
                                
                                <div class="book-cover">
                                    <?php if ($photo_devant) : ?>
                                        <img src="<?php echo esc_url($photo_devant['sizes']['medium']); ?>" 
                                             alt="<?php echo esc_attr($photo_devant['alt']); ?>"
                                             class="book-image">
                                    <?php else : ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-book"></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($show_status_icons) : ?>
                                    <div class="book-status-icons">
                                        <span class="status-icon owned" title="<?php _e('Possédé', 'bdcomic_theme'); ?>">
                                            <span class="dashicons dashicons-yes"></span>
                                        </span>
                                        
                                        <?php if ($is_read) : ?>
                                            <span class="status-icon read" title="<?php _e('Lu', 'bdcomic_theme'); ?>">
                                                <span class="dashicons dashicons-visibility"></span>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($is_loaned) : ?>
                                            <span class="status-icon loaned" title="<?php _e('Prêté', 'bdcomic_theme'); ?>">
                                                <span class="dashicons dashicons-share"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="book-info">
                                    <h4 class="book-title">
                                        <a href="<?php echo get_permalink($book->ID); ?>">
                                            <?php echo esc_html($titre); ?>
                                        </a>
                                    </h4>
                                    
                                    <?php if ($n_sortie) : ?>
                                        <div class="book-volume">
                                            <?php _e('Tome', 'bdcomic_theme'); ?> <?php echo esc_html($n_sortie); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Search functionality
    $('#collections-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        filterCollections();
    });
    
    // Filter functionality
    $('.filter-select, .filter-date').on('change', function() {
        filterCollections();
    });
    
    // View filter buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        filterCollections();
    });
    
    // Clear search
    $('.search-clear').on('click', function() {
        $('#collections-search').val('');
        filterCollections();
    });
    
    function filterCollections() {
        var searchTerm = $('#collections-search').val().toLowerCase();
        var maisonFilter = $('#filter-maison').val();
        var collectionFilter = $('#filter-collection').val();
        var artisteFilter = $('#filter-artiste').val();
        var dateFilter = $('#filter-date').val();
        var viewFilter = $('.filter-btn.active').data('filter');
        
        $('.collection-group').each(function() {
            var $collection = $(this);
            var showCollection = true;
            
            // Search filter
            if (searchTerm) {
                var collectionName = $collection.find('.collection-name').text().toLowerCase();
                var bookTitles = $collection.find('.book-title').text().toLowerCase();
                var bookVolumes = $collection.find('.book-volume').text().toLowerCase();
                
                if (collectionName.indexOf(searchTerm) === -1 && 
                    bookTitles.indexOf(searchTerm) === -1 && 
                    bookVolumes.indexOf(searchTerm) === -1) {
                    showCollection = false;
                }
            }
            
            // Collection filter
            if (collectionFilter && $collection.data('collection-id') != collectionFilter) {
                showCollection = false;
            }
            
            // View filter
            if (viewFilter !== 'all') {
                var hasMatchingBooks = false;
                $collection.find('.book-item').each(function() {
                    var $book = $(this);
                    var isRead = $book.data('read') == 1;
                    var isLoaned = $book.data('loaned') == 1;
                    
                    if (viewFilter === 'unread' && !isRead) {
                        hasMatchingBooks = true;
                    } else if (viewFilter === 'loaned' && isLoaned) {
                        hasMatchingBooks = true;
                    }
                });
                
                if (!hasMatchingBooks) {
                    showCollection = false;
                }
            }
            
            if (showCollection) {
                $collection.show();
            } else {
                $collection.hide();
            }
        });
        
        // Hide empty collections
        $('.collections-list').each(function() {
            var visibleCollections = $(this).find('.collection-group:visible').length;
            if (visibleCollections === 0) {
                $(this).find('.collections-empty').show();
            } else {
                $(this).find('.collections-empty').hide();
            }
        });
    }
});
</script>
