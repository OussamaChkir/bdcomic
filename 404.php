<?php
    get_header();

    wp_enqueue_style('404', get_template_directory_uri() . '/assets/css/Globals/404.css', array(), '1.0', 'all');
?>

<main class="page-404">
    <div class="container">
        <div class="block-container">
            <div class="header-wrapper">
                <div class="header">
                    <h1><?php _e('Error 404', 'korsch'); ?></h1>
                </div>

                <div class="subheader h3">
                    <?php _e('Unfortunately we could not find the page you requested.', 'korsch'); ?>
                </div>
            </div>
            
            <a class="btn btn-icon" href="/"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Back to home page', 'korsch'); ?></span></a>
        </div>
    </div>
</main>

<?php get_footer(); ?>