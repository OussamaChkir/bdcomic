<?php 
    $footer_logo = get_field('footer_logo', 'option');
    $addresses = get_field('addresses', 'option');
    $social_networks = get_field('social-networks', 'option');
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
            
            <div class="address-wrapper">
            <?php if ($addresses) : ?>
                <?php foreach ($addresses as $address) : ?>
                    <div class="footer-address">
                        <?php echo ($address['address']); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>  
            </div>
            
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

        </div>   
    </div>
</footer>