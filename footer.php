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