<?php 
    $prefooter = get_field('prefooter');
    $hide_prefooter = $prefooter['hide_prefooter'];

    if (!$hide_prefooter) :
        wp_enqueue_style('prefooter', get_template_directory_uri() . '/assets/css/Partials/prefooter.css', array(), '1.0', 'all');

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

                <?php if ($contact_form_page): ?>
                    <div class="contact-form-container">
                        <span class="icon-mail"></span>
                        <a class="link-icon h3 m-0" href="<?php echo $contact_form_page["url"]; ?>" target="<?php echo $contact_form_page["target"]; ?>"><span class="label"><?php _e('Contact Form', 'korsch'); ?></span><span class="icon icon-arrow-right"></span></a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($prefooter_image) : ?>
                <div class="image">
                    <?php echo wp_get_attachment_image( $prefooter_image['ID'], 'prefooter-image', false, array('loading' => 'lazy') ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif;
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

<?php wp_footer(); ?>
</body>
</html>