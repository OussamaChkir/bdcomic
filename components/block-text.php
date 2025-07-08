<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$block_text = get_field('text');

if ($block_text) :
    $header = $block_text['header'];
    $header_type = $block_text['header_type'];
    $subheader = $block_text['subheader'];
    $text = $block_text['text'];
    $second_text = $block_text['second_text'];
?>

    <div class="block-text <?php echo $background_color; ?>">
        <div class="container">
            <?php if ($header || $subheader || $text || $second_text): ?>
                <div class="header-wrapper">
                    <?php if ($header): ?>
                        <div class="header">
                            <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                        </div>
                    <?php endif; ?>

                    <?php if ($subheader): ?>
                        <div class="subheader h3">
                            <?php echo esc_html($subheader); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($text && $second_text): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text text-medium">
                                    <?php echo $text; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="second_text text-medium">
                                    <?php echo $second_text; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($text): ?>
                        <div class="text text-medium">
                            <?php echo $text; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>