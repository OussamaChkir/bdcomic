<?php
wp_enqueue_style('block-text-bild-teaser-mit-button', get_template_directory_uri() . '/assets/css/ContentElements/ce-text-bild-teaser-mit-button.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$text_bild_teaser_mit_button = get_field('text_bild_teaser_mit_button');

if ($text_bild_teaser_mit_button) :
    $header = $text_bild_teaser_mit_button['header'];
    $header_type = $text_bild_teaser_mit_button['header_type'];
    $subheader = $text_bild_teaser_mit_button['subheader'];
    $text = $text_bild_teaser_mit_button['text'];
    $button = $text_bild_teaser_mit_button['button'];
    $image = $text_bild_teaser_mit_button['image'];
?>

    <div class="block-text-bild-teaser-mit-button <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
        <div class="container">
            <div class="block-container">
                <?php if ($header): ?>
                    <div class="header">
                        <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                    </div>
                <?php endif; ?>

                <div class="primary-bg">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php if ($image) : ?>
                                <div class="image">
                                    <?php echo wp_get_attachment_image( $image['ID'], 'bild-teaser', false, array('loading' => 'lazy') ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-teaser-mit-button">
                                <?php if ($subheader): ?>
                                    <div class="subheader h3 m-0">
                                        <?php echo esc_html($subheader); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($text): ?>
                                    <div class="text text-medium">
                                        <?php echo $text; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($button) : ?>
                                    <a class="btn btn-icon" href="<?php echo $button["url"]; ?>" target="<?php echo $button["target"]; ?>"><span class="icon icon-arrow-right"></span><span class="label"><?php echo $button["title"]; ?></span></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>