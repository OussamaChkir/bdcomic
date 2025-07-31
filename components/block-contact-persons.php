<?php
wp_enqueue_style('prefooter', get_template_directory_uri() . '/assets/css/Partials/prefooter.css', array(), '1.0', 'all');
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');
wp_enqueue_style('block-contact-persons', get_template_directory_uri() . '/assets/css/ContentElements/ce-contact-persons.css', array(), '1.0', 'all');

wp_enqueue_script('prefooter', get_template_directory_uri() . '/assets/js/prefooter.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$contact_persons = get_field('contact_persons');

if ($contact_persons) :
    $header = $contact_persons['header'];
    $header_type = $contact_persons['header_type'];
    $subheader = $contact_persons['subheader'];
    $text = $contact_persons['text'];
?>

    <div class="block-contact-persons block-text prefooter <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
        <div class="container">
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

            <?php
            $contact_query = new WP_Query(array(
                'post_type' => 'contact_person',
                'posts_per_page' => -1,
            ));

            $regions = get_terms(array(
                'taxonomy'   => 'countries',
                'hide_empty' => false,
                'parent'     => 0
            ));  ?>

            <?php if ($contact_query->have_posts()) : ?>
                <div class="contact_person-container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-7">
                            <div id="contact-filter">
                                <div class="form-group">
                                    <label for="region-select"><?php _e('Region', 'korsch'); ?></label>
                                    <select id="region-select" class="form-control region-select">
                                        <option value=""><?php _e('Choose region', 'korsch'); ?></option>
                                        <?php foreach ($regions as $region): ?>
                                            <option value="<?php echo esc_attr($region->term_id); ?>">
                                                <?php echo esc_html($region->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="country-filter">
                                    <label for="country-select"><?php _e('Country', 'korsch'); ?></label>
                                    <select id="country-select" class="form-control country-select">
                                        <option value=""><?php _e('Choose country', 'korsch'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="contact-results-message" class="h4 m-0"><?php _e('We have found', 'korsch'); ?> <span id="results-count">0</span> <?php _e('results. Reach out to us!', 'korsch'); ?></div>

                    <div class="contact_persons">
                        <?php while ($contact_query->have_posts()) : $contact_query->the_post();
                            $job_title = get_field('job_title', get_the_ID());
                            $company = get_field('company', get_the_ID());
                            $address = get_field('address', get_the_ID());
                            $email = get_field('email', get_the_ID());
                            $phone = get_field('phone', get_the_ID());
                            $mobile = get_field('mobile', get_the_ID());
                            $terms = get_the_terms(get_the_ID(), 'countries');

                            $category_class = '';
                            if (!empty($terms) && !is_wp_error($terms)) {
                                foreach ($terms as $term) {
                                    $category_class .= ' cat-' . esc_attr($term->term_id);
                                }
                            }
                        ?>
                            <div class="contact-person h6 m-0<?php echo esc_attr($category_class); ?>">
                                <div>
                                    <div class="h5 m-0"><?php echo esc_html(get_the_title()); ?></div>
                                    <?php if ($job_title): ?><div><?php echo esc_html($job_title); ?></div><?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($company): ?><div><?php echo esc_html($company); ?></div><?php endif; ?>
                                    <?php if ($address): ?><div><?php echo $address; ?></div><?php endif; ?>
                                </div>
                                <div class="gap-8">
                                    <?php if ($email): ?><div><span class="icon-email"></span><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div><?php endif; ?>
                                    <?php if ($phone): ?><div><span class="icon-phone"></span><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></div><?php endif; ?>
                                    <?php if ($mobile): ?><div><span class="icon-mobile"></span><a href="tel:<?php echo esc_attr($mobile); ?>"><?php echo esc_html($mobile); ?></a></div><?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>