<?php
    get_header();

    wp_enqueue_style('block-header-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-header-image.css', array(), '1.0', 'all');
    wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');
    wp_enqueue_style('single-post', get_template_directory_uri() . '/assets/css/Globals/single-post.css', array(), '1.0', 'all');


    $author = get_field('author');
    $teaser_image = get_field('teaser_image');
    $description = get_field('description');
?>

<main class="post-single">
    <div class="main-inner">
        <?php if ($teaser_image): ?>
            <div class="block-header-image">
                <?php echo wp_get_attachment_image( $teaser_image['ID'], 'header-image', false, array('loading' => 'lazy') ); ?>
            </div>
        <?php elseif ( has_post_thumbnail() ) : ?>
            <div class="block-header-image">
                <?php the_post_thumbnail('header-image'); ?>
            </div>
        <?php endif; ?>

        <div class="post-content">
            <div class="block-text">
                <div class="container">
                    <div class="header-wrapper">
                        <div class="header">
                            <h1><?php the_title(); ?></h1>
                        </div>

                        <div class="subheader h3"><?php the_excerpt(); ?></div>

                        <div class="entry-meta text-medium">
                            <?php if (!empty($author)) : ?>
                                <span class="author"><?php _e('from', 'korsch'); ?> <?php echo esc_html($author); ?>, </span>
                            <?php endif; ?>
                            <span class="posted-on"><?php echo get_the_date('d.m.Y'); ?>, </span>
                            <span class="time-ago"><?php _e('Reading Time', 'korsch'); ?>: <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?></span>
                        </div>

                        <?php if (!empty($description)) : ?>
                            <div class="text text-medium">
                                <?php echo $description; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php the_content(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>