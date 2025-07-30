<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

wp_enqueue_style('block-downloads', get_template_directory_uri() . '/assets/css/ContentElements/ce-downloads.css', array(), '1.0', 'all');
wp_enqueue_script('block-downloads', get_template_directory_uri() . '/assets/js/ContentElements/ce-downloads.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$downloads = get_field('downloads');

if ($downloads) :
    $header = $downloads['header'];
    $header_type = $downloads['header_type'];
    $subheader = $downloads['subheader'];
    $text = $downloads['text'];

    $args = array(
        'post_type'      => 'download',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if( $query->have_posts() ): ?>
        <div class="block-text block-download <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
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

                            <div class="download-container row">
                                <?php
                                $post_count = 0; 

                                while( $query->have_posts() ): $query->the_post();
                                    $post_count++;

                                    $media = get_field('media', get_the_ID());
                                    $media_url = $media['url'] ?? '';
                                ?>
                                    <div class="download-teaser col-lg-4 col-md-6" style="display: <?php echo ($post_count > 9) ? 'none' : 'flex'; ?>;">
                                        <a class="download-content primary-bg" href="<?php echo esc_url($media_url); ?>" download>
                                            <?php 
                                            $categories = wp_get_object_terms(get_the_ID(), get_object_taxonomies(get_post_type()));
                                            if ( ! empty( $categories ) ) :
                                                $first_category = $categories[0]; ?>
                                                    
                                                <div class="categories h6"><?php echo esc_html($first_category->name); ?></div>
                                            <?php endif; ?>

                                            <div class="title h4 m-0"><?php the_title(); ?></div>

                                            <div class="icon icon-download-white"></div>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($query->found_posts > 9): ?>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-icon load-more-btn" href="#"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Load more', 'korsch'); ?></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>