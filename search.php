<?php get_header(); ?>
<div class="container">
    <h1><?php printf( __('Résultats de recherche pour : %s', 'bdcomic_theme'), get_search_query() ); ?></h1>
    <?php if (have_posts()) : ?>
        <ul class="search-results-list">
            <?php while (have_posts()) : the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
            <?php endwhile; ?>
        </ul>
        <?php the_posts_navigation(); ?>
    <?php else : ?>
        <p><?php _e('No results found.', 'bdcomic_theme'); ?></p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>