<?php
/**
 * The template for displaying the footer
 *
 * @package bdcomic_theme
 */

// Get ACF fields
$footer_copyright = get_field('copyright', 'option');
$footer_logo = get_field('footer_logo', 'option');
$social_networks = get_field('social-networks', 'option');

?>

<footer>
    <div class="container">
        <div class="footer-wrapper">
            <!-- Footer Logo -->
            <div class="footer-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo home_url(); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Footer Navigation -->
            <?php if (has_nav_menu('footer-menu')) : ?>
                <nav class="footer-navigation">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer-menu',
                        'menu_class' => 'menu',
                        'container' => false,
                        'depth' => 1,
                        'fallback_cb' => false
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

                        <!-- Social Networks -->
            <?php if ($social_networks) : ?>
                <div class="footer-social-networks">
                    <?php if (!empty($social_networks['facebook'])) : ?>
                        <a href="<?php echo esc_url($social_networks['facebook']); ?>" target="_blank" rel="noopener" class="social-link facebook">
                            <span>Facebook</span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($social_networks['instagram'])) : ?>
                        <a href="<?php echo esc_url($social_networks['instagram']); ?>" target="_blank" rel="noopener" class="social-link instagram">
                            <span>Instagram</span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($social_networks['youtube'])) : ?>
                        <a href="<?php echo esc_url($social_networks['youtube']); ?>" target="_blank" rel="noopener" class="social-link youtube">
                            <span>YouTube</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Footer Copyright -->
            <?php if ($footer_copyright) : ?>
                <div class="footer-copyright">
                    <p><?php echo wp_kses_post($footer_copyright); ?></p>
                </div>
            <?php else : ?>
                <div class="footer-copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'mondeft_theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<div class="backtotop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
    </svg>
</div>

<?php wp_footer(); ?>

</body>
</html>
