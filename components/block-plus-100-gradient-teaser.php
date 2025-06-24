<?php

wp_enqueue_style('block-100-gradient-teaser', get_template_directory_uri() . '/assets/css/ContentElements/ce-100-gradient-teaser.css', array(), '1.0', 'all');

$gradient_teaser = get_field('100_gradient_teaser');

if ($gradient_teaser) :
    $text = $gradient_teaser['text'];
    $image = $gradient_teaser['image'];
    $link = $gradient_teaser['link'];
?>

    <div class="block-100-gradient-teaser">
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
                <div class="col-xl-6">
                    <?php if ($text): ?>
                        <h2 class="text text-white m-0"><?php echo $text; ?></h2>
                    <?php endif; ?>

                    <?php if ($link): ?>
                        <a class="icon-arrow-right-white float-end" href="<?php echo $link["url"]; ?>" target="<?php echo $link["target"]; ?>"><span class="d-none"><?php echo $link["title"]; ?></span></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>