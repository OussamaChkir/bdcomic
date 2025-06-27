<?php

wp_enqueue_style('block-post-teaser-list', get_template_directory_uri() . '/assets/css/ContentElements/ce-post-teaser-list.css', array(), '1.0', 'all');

$post_teaser_list = get_field('post_teaser_list');

if ($post_teaser_list) :
    $header = $post_teaser_list['header'];
    $header_type = $post_teaser_list['header_type'];
    $subheader = $post_teaser_list['subheader'];
    $text = $post_teaser_list['text'];
    $button = $post_teaser_list['button'];

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    );

    $query = new WP_Query($args);

    if( $query->have_posts() ): ?>
        <div class="block-post-teaser-list">
            <div class="container">
                <div class="block-container">
                    <?php if ($header || $subheader || $text): ?>
                        <div class="header-wrapper">
                            <?php if ($header): ?>
                                <div class="header">
                                    <?php if ( is_front_page() ) : ?>
                                        <<?php echo $header_type; ?> class="h1"><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
                                    <?php else : ?>
                                        <<?php echo $header_type; ?>><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($subheader): ?>
                                <div class="subheader h3">
                                    <?php echo esc_html($subheader); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($text): ?>
                                <div class="text text-medium">
                                    <?php echo $text; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-teaser-container">
                        <div class="row">
                            <?php while( $query->have_posts() ): $query->the_post(); ?>
                                <div class="col-xl-4 col-md-6">
                                    <div class="post-teaser">
                                        <?php if( has_post_thumbnail() ): ?>
                                            <?php echo get_the_post_thumbnail('', 'post-teaser'); ?>
                                        <?php endif; ?>

                                        <div class="post-teaser-content primary-bg">
                                            <div class="post-meta h6">
                                                <?php $tags = get_the_tags();

                                                if ( ! empty( $tags ) ) :
                                                    $first_tag = $tags[0]; ?>
                                                    <span class="tag"><?php echo esc_html($first_tag->name); ?></span>
                                                <?php endif; ?>

                                                <span class="date"><?php echo get_the_date('d.m.Y'); ?></span>
                                            </div>

                                            <div class="title h4"><?php the_title(); ?></div>

                                            <div class="icon icon-arrow-right-white"></div>
                                        </div>

                                        <a class="post-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>

                    <?php if ($button) : ?>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-icon" href="<?php echo $button["url"]; ?>" target="<?php echo $button["target"]; ?>"><span class="icon icon-arrow-right"></span><span class="label"><?php echo $button["title"]; ?></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>