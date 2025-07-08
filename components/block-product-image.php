<?php 
wp_enqueue_style('block-product-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-product-image.css', array(), '1.0', 'all');

$product_image = get_field('product_image');

if ($product_image): 
    $image = $product_image['image'];
    $background_on_lower_half = $product_image['background_on_lower_half'];
?>

    <?php if ($image) : ?>
        <div class="block-product-image">
            <div class="container">
                <?php echo wp_get_attachment_image( $image['ID'], 'product-image', false, array('loading' => 'lazy') ); ?>
            </div>

            <?php if ($background_on_lower_half) : ?>
                <div class="overlay"></div>
            <?php endif; ?>   
        </div>
    <?php endif; ?>
<?php endif; ?>