<?php

wp_enqueue_style('block-text-bild-teaser-mit-button', get_template_directory_uri() . '/assets/css/ContentElements/ce-text-bild-teaser-mit-button.css', array(), '1.0', 'all');

$text_bild_teaser_mit_button = get_field('text_bild_teaser_mit_button');

if ($text_bild_teaser_mit_button) :
    $header = $text_bild_teaser_mit_button['header'];
    $header_type = $text_bild_teaser_mit_button['header_type'];
    $subheader = $text_bild_teaser_mit_button['subheader'];
    $text = $text_bild_teaser_mit_button['text'];
    $button = $text_bild_teaser_mit_button['button'];
    $image = $text_bild_teaser_mit_button['image'];
?>

    <div class="block-text-bild-teaser-mit-button">
        <div class="container">
            <div class="block-container">
                <?php if ($header): ?>
                    <div class="header">
                        <<?php echo $header_type; ?> class="h1"><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
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