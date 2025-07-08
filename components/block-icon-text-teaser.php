<?php
wp_enqueue_style('block-icon-text-teaser', get_template_directory_uri() . '/assets/css/ContentElements/ce-icon-text-teaser.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
if ($common_properties) {
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';
}

$icon_text_teaser = get_field('icon_text_teaser');

if ($icon_text_teaser) : ?>
    <div class="block-icon-text-teaser <?php echo $background_color; ?>">
        <div class="container">
            <div class="row">
                <?php foreach ($icon_text_teaser as $item):
                    $icon = $item['icon'];
                    $header = $item['header'];
                    $text = $item['text'];
                ?>

                    <div class="col-xl-3 col-lg-6 item">
                        <div class="icon-text-teaser header-wrapper">
                            <?php if ($icon) : ?>
                                <div class="icon <?php echo $icon; ?>"></div>
                            <?php endif; ?>

                            <div class="teaser-content">
                                <?php if ($header): ?>
                                    <div class="title h4">
                                        <?php echo esc_html($header); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($text): ?>
                                    <div class="text h6">
                                        <?php echo esc_html($text); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>