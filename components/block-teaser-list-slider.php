<?php
wp_enqueue_style('block-teaser-list-slider', get_template_directory_uri() . '/assets/css/ContentElements/ce-teaser-list-slider.css', array(), '1.0', 'all');

$teaser_list_slider = get_field('teaser_list_slider');

if ($teaser_list_slider) :
    $header = $teaser_list_slider['header'];
    $header_type = $teaser_list_slider['header_type'];
    $subheader = $teaser_list_slider['subheader'];
    $text = $teaser_list_slider['text'];
    $is_slider = $teaser_list_slider['is_slider'];
    $teasers = $teaser_list_slider['teasers'];
?>

    <div class="block-teaser-list-slider">
        <div class="container">
            <div class="block-container">
                <?php if ($header || $subheader || $text): ?>
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

                        <?php if ($text): ?>
                            <div class="text text-medium">
                                <?php echo $text; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($teasers) : ?>
                    <div class="teaser-list-slider">
                        <?php foreach ($teasers as $item):
                            $headerItem = $item['header'];
                            $image = $item['image'];
                            $textItem = $item['text'];
                        ?>

                            <div class="row">
                                <div class="col-lg-6">
                                    <?php if ($image) : ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'image-list', false, array('loading' => 'lazy') ); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-6">
                                    <div class="teaser-content">
                                        <?php if ($headerItem): ?>
                                            <div class="title h3"><b><?php echo esc_html($headerItem); ?></b></div>
                                        <?php endif; ?>

                                        <?php if ($textItem): ?>
                                            <div class="text text-medium">
                                                <?php echo $textItem; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>