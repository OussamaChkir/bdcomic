<?php
wp_enqueue_style('block-text-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-text-image.css', array(), '1.0', 'all');

$text_image = get_field('text_image');

if ($text_image) :
    $header = $text_image['header'];
    $header_type = $text_image['header_type'];
    $subheader = $text_image['subheader'];
    $text = $text_image['text'];
    $image = $text_image['image'];
    $image_position = $text_image['image_position'];
?>

    <div class="block-text-image <?php if ($image_position) {echo $image_position;} ?>">
        <div class="container">
            <div class="row text-image-row">
                <div class="col-lg-6">
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
                </div>
            </div>

            <?php if ($image) : ?>
                <div class="image">
                    <?php echo wp_get_attachment_image( $image['ID'], 'bild-teaser', false, array('loading' => 'lazy') ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>