<?php 
/**************************************************
 * 
 * Add a Anchor links
 * 
**************************************************/

wp_enqueue_style('anchor-links', get_template_directory_uri() . '/assets/css/Partials/anchor-links.css', array(), '1.0', 'all');
wp_enqueue_script('anchor-links', get_template_directory_uri() . '/assets/js/anchor-links.js', array(), '1.0', true);

function anchor_links() {
    global $post;

    if (!$post) return;

    $blocks = parse_blocks(get_the_content());
    $anchor_links = [];

    foreach ($blocks as $block) {
        if (!isset($block['attrs']['data'])) continue;

        $data = $block['attrs']['data'];

        // 1. From common_properties group
        $show = $data['common_properties_show_in_anchor_navi'] ?? '';
        $label = $data['common_properties_anchor_navi_label'] ?? '';

        if ($show === '1' && !empty($label)) {
            $anchor_links[] = $label;
        }

        // 2. Repeater inside Group: accordeon_accordeons
        if (!empty($data['accordeon_accordeons']) && is_numeric($data['accordeon_accordeons'])) {
            $count = intval($data['accordeon_accordeons']);

            for ($i = 0; $i < $count; $i++) {
                $item_show  = $data["accordeon_accordeons_{$i}_show_in_anchor_navi"] ?? '';
                $item_label = $data["accordeon_accordeons_{$i}_anchor_navi_label"] ?? '';

                if ($item_show === '1' && !empty($item_label)) {
                    $anchor_links[] = $item_label;
                }
            }
        }
    }

    // 3. From prefooter group on page
    $prefooter = get_field('prefooter', $post->ID);
    if (!empty($prefooter['show_in_anchor_navi']) && !empty($prefooter['anchor_navi_label'])) {
        $anchor_links[] = $prefooter['anchor_navi_label'];
    }

    // 4. Output
    if (!empty($anchor_links)) :
        ?>
        <div class="anchor-links h6 mb-0">
            <?php foreach ($anchor_links as $label) : ?>
                <div><a href="#<?php echo esc_attr(sanitize_title($label)); ?>"><?php echo esc_html($label); ?></a></div>
            <?php endforeach; ?>
        </div>
        <?php
    endif;
}
?>