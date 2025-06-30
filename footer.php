<?php 
    $prefooter = get_field('prefooter');

    if ($prefooter) :
        $hide_prefooter = $prefooter['hide_prefooter'];
    
        if (!$hide_prefooter) :
            wp_enqueue_style('prefooter', get_template_directory_uri() . '/assets/css/Partials/prefooter.css', array(), '1.0', 'all');
            wp_enqueue_script('prefooter', get_template_directory_uri() . '/assets/js/prefooter.js', array(), '1.0', true);

            $prefooter_image = $prefooter['prefooter_image'];

            $header = get_field('header', 'option');
            $subheader = get_field('subheader', 'option');
            $text = get_field('text', 'option');
            $contact_form_page = get_field('contact_form_page', 'option');
        ?>

            <div class="prefooter">
                <div class="container">
                    <?php if ($header || $subheader || $text): ?>
                        <div class="row">
                            <div class="col-xl-9">
                                <div class="header-wrapper">
                                    <?php if ($header): ?>
                                        <div class="header">
                                            <h2 class="h1"><?php echo esc_html($header); ?></h2>
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
                            </div>
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
                                    $job_title = get_field('job_title');
                                    $company = get_field('company');
                                    $address = get_field('address');
                                    $email = get_field('email');
                                    $phone = get_field('phone');
                                    $mobile = get_field('mobile');
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
                                            <?php if ($mobile): ?><div><span class="icon-phone"></span><a href="tel:<?php echo esc_attr($mobile); ?>"><?php echo esc_html($mobile); ?></a></div><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    

                    <?php if ($contact_form_page): ?>
                        <div class="contact-form-container">
                            <span class="icon-mail"></span>
                            <a class="link-icon h3 m-0" href="<?php echo $contact_form_page["url"]; ?>" target="<?php echo $contact_form_page["target"]; ?>"><span class="label"><?php _e('Contact Form', 'korsch'); ?></span><span class="icon icon-arrow-right"></span></a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="image">
                    <?php if ($prefooter_image) : ?>
                        <?php echo wp_get_attachment_image( $prefooter_image['ID'], 'prefooter-image', false, array('loading' => 'lazy') ); ?>
                    <?php else : ?>
                        <img src="<?= get_template_directory_uri(); ?>/assets/img/prefooter.png" alt="<?php bloginfo('name'); ?>" loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
            </div>
        <?php endif;
    endif;
?>


<?php 
    $footer_logo = get_field('footer_logo', 'option');
    $addresses = get_field('addresses', 'option');
    $social_networks = get_field('social_networks', 'option');
?>

<footer>
    <div class="container">
        <div class="footer-wrapper">
            <div class="footer-logo">
                <a href="<?php echo home_url(); ?>">
                    <?php if ($footer_logo) : ?>
                        <?php echo wp_get_attachment_image( $footer_logo['ID'], 'full', false, array('loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                    <?php else : ?>
                        <?php bloginfo('name'); ?>
                    <?php endif; ?>
                </a>
            </div>

            <?php if ($addresses) : ?>
                <?php foreach ($addresses as $item): 
                    $address = $item['address'];
                ?>
                    <?php if ($address) : ?>
                        <div class="address">
                            <?php echo $address; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($social_networks) :
                $youtube = $social_networks['youtube'];
                $linkedin = $social_networks['linkedin'];
                $wikipedia = $social_networks['wikipedia'];
            ?>
                <div class="social-networks">
                    <?php if ($youtube) : ?>
                        <a class="social icon-youtube" href="<?php echo $youtube; ?>" target="_blank" rel="noopener"><?php _e('Youtube', 'korsch'); ?></a>
                    <?php endif; ?>

                    <?php if ($linkedin) : ?>
                        <a class="social icon-linkedin" href="<?php echo $linkedin; ?>" target="_blank" rel="noopener"><?php _e('Linkedin', 'korsch'); ?></a>
                    <?php endif; ?>

                    <?php if ($wikipedia) : ?>
                        <a class="social icon-wikipedia" href="<?php echo $wikipedia; ?>" target="_blank" rel="noopener"><?php _e('Wikipedia', 'korsch'); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <nav class="footer-menu">
                <?php if (has_nav_menu('footer-menu')) {
                    wp_nav_menu([
                        'theme_location' => 'footer-menu',
                        'menu_class' => 'menu nav',
                        'depth' => 1
                    ]); 
                } ?>
            </nav>
        </div>
    </div>
</footer>

<div class="backtotop">
    <span class="icon-backtotop"></span>
</div>

<?php wp_footer(); ?>
</body>
</html>