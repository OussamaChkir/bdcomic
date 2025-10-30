<?php get_header(); ?>

<main>
  <div class="main-inner">
    <h1 class="d-none"><?php bloginfo('name'); ?></h1>

    <?php while (have_posts()) : the_post();

        $blocks = parse_blocks(get_the_content());
        $header_blocks = ['acf/header-image', 'acf/header-video'];
        
        ob_start();
        anchor_links();
        $anchor_output = trim(ob_get_clean());
        $should_output_anchors = !empty($anchor_output);

        $has_rendered_header = false;

        if (empty($blocks)) {
            if ($should_output_anchors) {
            echo '<div class="content-page">';
            echo $anchor_output;
            echo '</div>';
            }
        } else {
            foreach ($blocks as $index => $block) {
            $block_name = $block['blockName'] ?? '';

            if (!$has_rendered_header && in_array($block_name, $header_blocks, true)) {
                echo render_block($block);
                $has_rendered_header = true;
                unset($blocks[$index]);
                break;
            }
            }

            echo '<div class="content-page">';
            if ($should_output_anchors) {
            echo $anchor_output;
            }

            foreach ($blocks as $block) {
            echo render_block($block);
            }

            echo '</div>';
        }

        endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>