<?php
if ( ! wp_style_is( 'slick', 'enqueued' ) ) {
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/plugins/slick.css', array(), '1.0', 'all');
}
if ( ! wp_script_is( 'slick', 'enqueued' ) ) {
    wp_enqueue_script('slick', get_template_directory_uri() . '/assets/plugins/slick/slick.min.js', array(), '1.0', true);
}

wp_enqueue_style('block-teaser-list-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-teaser-list-slider.css', array(), '1.0', 'all');
wp_enqueue_style('block-teaser-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-teaser-slider.css', array(), '1.0', 'all');
wp_enqueue_script('block-teaser-slider', get_template_directory_uri() . '/assets/js/ContentElements/ce-teaser-list-slider.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$teaser_slider = get_field('teaser_slider');

if ($teaser_slider) :
    $header = $teaser_slider['header'];
    $header_type = $teaser_slider['header_type'];
    $text_top = $teaser_slider['text_top'];
    $teasers = $teaser_slider['teasers'];
    $text_bottom = $teaser_slider['text_bottom'];
?>

    <div class="block-teaser-slider block-teaser-list-slider <?php echo $background_color; ?>">
        <div class="container">
            <div class="block-container">
                <?php if ($header): ?>
                    <div class="header">
                        <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                    </div>
                <?php endif; ?>

                <?php if ($text_top) : ?>
                    <?php foreach ($text_top as $item):
                        $subheader = $item['subheader'];
                        $text = $item['text'];
                    ?>
                        <div class="header-wrapper">
                            <?php if ($subheader): ?>
                                <div class="subheader h3 m-0">
                                    <b><?php echo esc_html($subheader); ?></b>
                                </div>
                            <?php endif; ?>

                            <?php if ($text): ?>
                                <div class="text text-medium">
                                    <?php echo $text; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($teasers) : ?>
                    <div class="teaser-slider-wrapper">
                        <div class="teaser-slider-control">
                            <div class="teaser-slider-dots"></div>
                            <div class="teaser-slider-nav"></div>
                        </div>
                        
                        <div class="teaser-list-slider js-is-slider">
                            <?php foreach ($teasers as $item):
                                $headerItem = $item['header'];
                                $image = $item['image'];
                                $textItem = $item['text'];
                            ?>
                                <div class="teaser-item">
                                    <?php if ($image) : ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'product-image', false, array('loading' => 'lazy') ); ?>
                                    <?php endif; ?>

                                    <div class="teaser-content primary-bg">
                                        <?php if ($headerItem): ?>
                                            <div class="title h3 m-0"><b><?php echo esc_html($headerItem); ?></b></div>
                                        <?php endif; ?>

                                        <?php if ($textItem): ?>
                                            <div class="text text-medium">
                                                <?php echo $textItem; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endif; ?>

                <?php if ($text_bottom) : ?>
                    <div class="row">
                        <div class="col-lg-9">
                            <?php foreach ($text_bottom as $item):
                                $subheader_bottom = $item['subheader'];
                                $text_bottom = $item['text'];
                            ?>
                                <div class="header-wrapper">
                                    <?php if ($subheader_bottom): ?>
                                        <div class="subheader h3 m-0">
                                            <b><?php echo esc_html($subheader_bottom); ?></b>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($text_bottom): ?>
                                        <div class="text text-medium">
                                            <?php echo $text_bottom; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>