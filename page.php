<?php get_header(); ?>

<main>
    <div class="main-inner">
        <?php
        // do the loop
        while( have_posts() ): the_post(); ?>
            <?php the_content(); ?>      
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>