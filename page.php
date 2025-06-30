<?php get_header(); ?>

<main>
    <div class="main-inner">
        <h1 class="d-none"><?php bloginfo('name'); ?></h1>
        <?php
        // do the loop
        while( have_posts() ): the_post(); ?>
            <?php the_content(); ?>      
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>