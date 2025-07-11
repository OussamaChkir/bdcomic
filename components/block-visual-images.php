<?php 
wp_enqueue_style('block-visual-images', get_template_directory_uri() . '/assets/css/ContentElements/ce-visual-images.css', array(), '1.0', 'all');

$visual_images = get_field('visual_images');

if ($visual_images): 
    $image_1 = $visual_images['image_1'];
    $image_2 = $visual_images['image_2'];
    $layout = $visual_images['layout'];
?>

    <div class="block-visual-images">
        <?php if ($image_1 && $image_2) : ?>
            <div class="row">
                <div class="col-md-<?php if ($layout == "66-33") : ?>8<?php else : ?>4<?php endif; ?>">
                    <?php echo wp_get_attachment_image( $image_1['ID'], 'product-image', false, array('loading' => 'lazy') ); ?>
                </div>
                <div class="col-md-<?php if ($layout == "66-33") : ?>4<?php else : ?>8<?php endif; ?>">
                    <?php echo wp_get_attachment_image( $image_2['ID'], 'product-image', false, array('loading' => 'lazy') ); ?>
                </div>
            </div>
        <?php else : ?>
            <div class="one-image">
                <?php if ($image_1) : ?>
                    <?php echo wp_get_attachment_image( $image_1['ID'], 'header-image', false, array('loading' => 'lazy') ); ?>
                <?php elseif ($image_2) : ?>
                    <?php echo wp_get_attachment_image( $image_2['ID'], 'header-image', false, array('loading' => 'lazy') ); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>