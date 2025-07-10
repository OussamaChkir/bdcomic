<?php
wp_enqueue_style('block-100-gradient-teaser', get_template_directory_uri() . '/assets/css/ContentElements/ce-100-gradient-teaser.css', array(), '1.0', 'all');

$gradient_teaser = get_field('100_gradient_teaser');

if ($gradient_teaser) :
    $header = $gradient_teaser['header'];
    $subheader = $gradient_teaser['subheader'];
    $text = $gradient_teaser['text'];
    $image = $gradient_teaser['image'];
    $link = $gradient_teaser['link'];
?>

    <div class="block-100-gradient-teaser <?php if ($subheader && $text): ?>lessSpace<?php endif; ?>">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <?php if ($image): ?>
                        <div class="icon-image">
                            <div class="icon icon-plus"></div>
                            <div class="image">
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array('loading' => 'eager', 'alt' => esc_attr(get_bloginfo('name')) ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-xl-6 p-xl-0">
                    <div class="header-wrapper">
                        <?php if ($header): ?>
                            <h2 class="header text-white m-0"><?php echo esc_html($header); ?></h2>
                        <?php endif; ?>

                        <?php if ($subheader): ?>
                            <div class="subheader h3 text-white">
                                <?php echo esc_html($subheader); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($text): ?>
                            <div class="text text-medium text-white m-0"><?php echo $text; ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($link): ?>
                        <a class="icon-arrow-right-white float-end" href="<?php echo $link["url"]; ?>" target="<?php echo $link["target"]; ?>"><span class="d-none"><?php echo $link["title"]; ?></span></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>