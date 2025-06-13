<?php get_header(); ?>

<main class="page-404">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php _e('Error 404', 'korsch'); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p><?php _e('Unfortunately we could not find the page you requested.', 'korsch'); ?></p>
                
                <a class="btn" href="/"><?php _e('Back to home page', 'korsch'); ?></a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>