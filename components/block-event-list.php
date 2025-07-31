<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');
// wp_enqueue_style('block-contact-persons', get_template_directory_uri() . '/assets/css/ContentElements/ce-contact-persons.css', array(), '1.0', 'all');

// wp_enqueue_script('prefooter', get_template_directory_uri() . '/assets/js/prefooter.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$event_list = get_field('event_list');

if ($event_list) :
    $header = $event_list['header'];
    $header_type = $event_list['header_type'];
    $subheader = $event_list['subheader'];
    $text = $event_list['text'];
    $image = $event_list['image'];
?>

<?php
    $event_query = new WP_Query(array(
        'post_type' => 'event',
        'posts_per_page' => -1,
    ));

    $locations = get_terms(array(
        'taxonomy'   => 'location',
        'hide_empty' => false
    ));
    
    $types = get_terms(array(
        'taxonomy'   => 'type',
        'hide_empty' => false
    ));
?>

    <div class="block-event-list block-text <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
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
                            <div class="subheader h3 m-0">
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

                <div class="row">
                    <div class="col-md-6">
                        <?php if ($image) : ?>
                            <div class="image">
                                <?php echo wp_get_attachment_image( $image['ID'], 'image-list', false, array('loading' => 'lazy') ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div id="event-filter">
                            <div class="form-group">
                                <label for="location-select"><?php _e('Location', 'korsch'); ?></label>
                                <select id="location-select" class="form-control location-select">
                                    <option value=""><?php _e('Choose locations', 'korsch'); ?></option>
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?php echo esc_attr($location->term_id); ?>">
                                            <?php echo esc_html($location->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type-select"><?php _e('Type', 'korsch'); ?></label>
                                <select id="type-select" class="form-control type-select">
                                    <option value=""><?php _e('Choose type', 'korsch'); ?></option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?php echo esc_attr($type->term_id); ?>">
                                            <?php echo esc_html($type->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($event_query->have_posts()) : ?>
                    <div class="event-container">
                        <?php while ($event_query->have_posts()) : $event_query->the_post();
                            $event_subtitle = get_field('subtitle', get_the_ID());
                            $start_date = get_field('start_date', get_the_ID());
                            $end_date = get_field('end_date', get_the_ID());
                            $link = get_field('link', get_the_ID());

                            $terms_locations = get_the_terms(get_the_ID(), 'location');
                            $location_class = '';
                            if (!empty($terms_locations) && !is_wp_error($terms_locations)) {
                                foreach ($terms_locations as $location) {
                                    $location_class .= ' location-' . esc_attr($location->term_id);
                                }
                            }

                            $terms_types = get_the_terms(get_the_ID(), 'type');
                            $type_class = '';
                            if (!empty($terms_types) && !is_wp_error($terms_types)) {
                                foreach ($terms_types as $type) {
                                    $type_class .= ' type-' . esc_attr($type->term_id);
                                }
                            }
                        ?>
                            <div class="event-teaser primary-bg h6 m-0<?php echo esc_attr($location_class); ?><?php echo esc_attr($type_class); ?>">
                                <div>
                                    <div class="h5 m-0"><?php echo esc_html(get_the_title()); ?></div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>