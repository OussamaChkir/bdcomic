<?php

wp_enqueue_style('block-teaser-list-v1', get_template_directory_uri() . '/assets/css/ContentElements/ce-teaser-list-v1.css', array(), '1.0', 'all');

$teaser_list_v1 = get_field('teaser_list_v1');

if ($teaser_list_v1) :
    $header = $teaser_list_v1['header'];
    $header_type = $teaser_list_v1['header_type'];
    $subheader = $teaser_list_v1['subheader'];
    $text = $teaser_list_v1['text'];
    $teasers = $teaser_list_v1['teasers'];
    $button = $teaser_list_v1['button'];
?>

    <div class="block-teaser-list-v1">
        <div class="container">
            <div class="block-container">
                <?php if ($header || $subheader || $text): ?>
                    <div class="header-wrapper">
                        <?php if ($header): ?>
                            <div class="header">
                                <<?php echo $header_type; ?> class="h1"><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
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
                    <div class="teasers-container">
                        <div class="row">

                            <?php foreach ($teasers as $item):
                                $image = $item['image'];
                                $headerItem = $item['header'];
                                $subheaderItem = $item['subheader'];
                            ?>

                                <div class="col-md-3 col-teaser">
                                    <div class="teaser">
                                        <?php if ($image) : ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'image-teaser', false, array('loading' => 'lazy') ); ?>
                                        <?php endif; ?>

                                        <div class="teaser-content">
                                            <?php if ($headerItem): ?>
                                                <div class="title h5">
                                                    <?php echo esc_html($headerItem); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($subheaderItem): ?>
                                                <p><?php echo esc_html($subheaderItem); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($button) : ?>
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-icon" href="<?php echo $button["url"]; ?>" target="<?php echo $button["target"]; ?>"><span class="icon icon-arrow-right"></span><span class="label"><?php echo $button["title"]; ?></span></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>