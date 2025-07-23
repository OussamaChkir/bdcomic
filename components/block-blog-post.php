<?php
wp_enqueue_style('block-blog-post', get_template_directory_uri() . '/assets/css/ContentElements/ce-blog-post.css', array(), '1.0', 'all');
wp_enqueue_script('block-blog-post', get_template_directory_uri() . '/assets/js/ContentElements/ce-blog-post.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$blog_post = get_field('blog_post');

if ($blog_post) :
    $header = $blog_post['header'];
    $header_type = $blog_post['header_type'];
    $subheader = $blog_post['subheader'];
    $text = $blog_post['text'];

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'meta_key'       => 'is_featured',
        'orderby'        => array(
            'meta_value_num' => 'DESC',  // is_featured = true first
            'date'            => 'DESC'  // then newest to oldest
        )
    );

    $query = new WP_Query($args);

    if( $query->have_posts() ): ?>
        <div class="block-blog-post <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
            <div class="container">
                <div class="block-container">
                    <?php if ($header || $subheader || $text): ?>
                        <div class="header-wrapper">
                            <?php if ($header): ?>
                                <div class="header">
                                    <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                                </div>
                            <?php endif; ?>

                            <?php if ($subheader): ?>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="subheader h3 m-0">
                                            <?php echo esc_html($subheader); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($text): ?>
                                <div class="text text-medium">
                                    <?php echo $text; ?>
                                </div>
                            <?php endif; ?>

                            <div class="blog-container-filter">
                                <button class="btn filter-btn h6 active m-0" data-tag="all"><?php _e('All', 'korsch'); ?></button>

                                <?php $tags = get_tags();
                                foreach ($tags as $tag): ?>
                                    <button class="btn filter-btn h6 m-0" data-tag="<?php echo esc_attr($tag->slug); ?>">
                                        <?php echo esc_html($tag->name); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="blog-container">
                        <?php
                        $post_count = 0; 

                        while( $query->have_posts() ): $query->the_post();
                            $post_count++; 

                            $tag_class = '';
                            $tag_ids = wp_get_post_tags(get_the_ID(), array('fields' => 'ids'));
                            if ($tag_ids) {
                                $tags = get_terms(array(
                                    'taxonomy' => 'post_tag',
                                    'include'  => $tag_ids,
                                ));

                                if ( ! empty( $tags ) ) {
                                    foreach ($tags as $tag) {
                                        $tag_class .= ' tag-' . esc_attr($tag->slug);
                                    }
                                }
                            }

                            $is_featured = get_field('is_featured', get_the_ID());
                            $featured_class = $is_featured ? ' is-featured' : '';
                            $author = get_field('author', get_the_ID());
                        ?>
                            <a class="post-teaser<?php if ( ! has_post_thumbnail() ): ?> noImg<?php endif; ?> <?php echo esc_attr($tag_class . $featured_class); ?>" style="display: <?php echo ($post_count > 6) ? 'none' : 'flex'; ?>;" href="<?php the_permalink(); ?>">
                                <div class="image<?php if ( ! has_post_thumbnail() ): ?> gradient-bg<?php endif; ?>">
                                    <?php if( has_post_thumbnail() ): ?>
                                        <?php echo get_the_post_thumbnail('', 'post-teaser'); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="post-teaser-content primary-bg">
                                    <div class="post-meta h6 m-0">
                                        <?php 
                                        if ( ! empty( $tags ) ) : ?>
                                            <span class="tags">
                                                <?php foreach ( $tags as $tag ) : ?>
                                                    <span class="tag"><?php echo esc_html($tag->name); ?></span>
                                                <?php endforeach; ?>
                                            </span>
                                        <?php endif; ?>

                                        <span class="post-time-ago"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?></span>

                                        <span class="date"><?php echo get_the_date('d.m.Y'); ?></span>
                                    </div>

                                    <div class="title h4 m-0"><?php the_title(); ?></div>

                                    <?php if (!empty($author)) : ?><div class="author h6 m-0"><?php echo esc_html($author); ?></div><?php endif; ?>

                                    <div class="icon icon-arrow-right-white"></div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>

                    <div class="d-flex justify-content-center">
                        <a class="btn btn-icon load-more-btn" href="#"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Load more', 'korsch'); ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>