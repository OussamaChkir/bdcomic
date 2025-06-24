<?php

wp_enqueue_style('block-teaser-list-v2', get_template_directory_uri() . '/assets/css/ContentElements/ce-teaser-list-v2.css', array(), '1.0', 'all');

$teaser_list_v2 = get_field('teaser_list_v2');

if ($teaser_list_v2) :
    $header = $teaser_list_v2['header'];
    $header_type = $teaser_list_v2['header_type'];
    $subheader = $teaser_list_v2['subheader'];
    $text = $teaser_list_v2['text'];
    $teasers = $teaser_list_v2['teasers'];
    $button = $teaser_list_v2['button'];
?>

    <div class="block-teaser-list-v2">
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
                    <div class="teasers-list-container">
                        <div class="row">
                            <?php foreach ($teasers as $item):
                                $image = $item['image'];
                                $icon = $item['icon'];
                                $headerItem = $item['header'];
                                $subheaderItem = $item['subheader'];
                                $buttonItem = $item['button'];
                            ?>

                                <div class="col-lg-6">
                                    <div class="teaser">
                                        <?php if ($image) : ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'image-list', false, array('loading' => 'lazy') ); ?>
                                        <?php endif; ?>

                                        <div class="teaser-content primary-bg">
                                            <?php if ($icon) : ?>
                                                <div class="<?php echo $icon; ?>"></div>
                                            <?php endif; ?>

                                            <?php if ($headerItem): ?>
                                                <div class="title h2"><?php echo esc_html($headerItem); ?></div>
                                            <?php endif; ?>

                                            <?php if ($subheaderItem): ?>
                                                <div class="subheader h4"><?php echo esc_html($subheaderItem); ?></div>
                                            <?php endif; ?>

                                            <?php if ($buttonItem): ?>
                                                <div class="icon icon-arrow-right-white"></div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($buttonItem): ?>
                                            <a class="teaser-link" href="<?php echo $buttonItem["url"]; ?>" target="<?php echo $buttonItem["target"]; ?>"><?php echo $buttonItem["title"]; ?></a>
                                        <?php endif; ?>
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