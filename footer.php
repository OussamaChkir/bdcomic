<?php 
    $show_prefooter = get_field('show_prefooter', 'option');
    $title_prefooter = get_field('title_prefooter', 'option');
    $logos = get_field('logos', 'option');

    $footer_logo = get_field('footer_logo', 'option');
    $copyright = get_field('copyright', 'option');

    $whatsapp = get_field('whatsapp', 'option');
    $mail = get_field('mail', 'option');
?>

<?php if ($show_prefooter): ?>
    <div class="prefooter">
        <div class="container">
            <?php if ($title_prefooter) : ?>
                <div class="title h5 text-center"><?php echo esc_html($title_prefooter); ?></div>
            <?php endif; ?>

            <?php if ($logos): ?>
                <div class="logos-wrapper">
                    <?php foreach ($logos as $logo): ?>
                        <div class="logo">
                            <?php echo wp_get_attachment_image( $logo['ID'], 'prefooter-logo', false, array('loading' => 'eager') ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?> 
        </div>
    </div>
<?php endif; ?>

<footer>
    <div class="container">
        <div class="footer-wrapper">
            <div class="footer-left-side">
                <div class="footer-logo">
                    <a href="<?php echo home_url(); ?>">
                        <?php if ($footer_logo) : ?>
                            <?php echo wp_get_attachment_image( $footer_logo['ID'], 'site-logo', false, array('loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                        <?php else : ?>
                            <?php bloginfo('name'); ?>
                        <?php endif; ?>
                    </a>
                </div>

                <?php if ( is_active_sidebar( 'footer-left-side' ) ) :
                    dynamic_sidebar( 'footer-left-side' );
                endif; ?>
            </div>

            <div class="footer-right-side">
                <?php if ( is_active_sidebar( 'footer-right-side' ) ) :
                    dynamic_sidebar( 'footer-right-side' );
                endif; ?>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="copyright">
                <?php if ($copyright) : ?>
                    <?php echo esc_html($copyright); ?>
                <?php else : ?>
                    &copy; <?php echo date('Y'); ?> - <?php bloginfo('name'); ?>
                <?php endif; ?>	
            </div>

            <div class="bottom-menu">
                <?php if (has_nav_menu('bottom-menu')) {
                    wp_nav_menu([
                        'theme_location' => 'bottom-menu',
                        'menu_class' => 'menu',
                        'depth' => 1
                    ]); 
                } ?>
            </div>
        </div>
    </div>
</footer>

<div class="sticky-side">
    <?php if ($whatsapp) : ?>
        <div class="sticky-item whatsapp">
            <div class="icon icon-whatsapp"></div>
            <div class="title"><?php _e('Whatsapp', 'degesa'); ?></div>
            <a class="link" href="<?php echo esc_html($whatsapp); ?>" target="_blank" rel="noopener"><?php _e('Whatsapp', 'degesa'); ?></a>
        </div>
    <?php endif; ?>
    
    <?php if ($mail) : ?>
        <div class="sticky-item mail">
            <div class="icon icon-mail"></div>
            <div class="title"><?php _e('Mail', 'degesa'); ?></div>
            <a class="link" href="mailto:<?php echo esc_html($mail); ?>"><?php _e('Mail', 'degesa'); ?></a>
        </div>
    <?php endif; ?>

    <div class="sticky-item backtotop">
        <div class="icon icon-backtotop"></div>
        <div class="title"><?php _e('Hochscrollen', 'degesa'); ?></div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>