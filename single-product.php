<?php
    get_header();

    wp_enqueue_style('single-product', get_template_directory_uri() . '/assets/css/Globals/single-product.css', array(), '1.0', 'all');


    $subtitle = get_field('subtitle');
    $header_image = get_field('header_image');
    $product_properties = get_field('product_properties');
?>

<main class="product-single">
    <div class="main-inner">
        <div class="product-header">
            <?php if ($header_image): ?>
                <div class="header-image">
                    <?php echo wp_get_attachment_image( $header_image['ID'], 'header-image', false, array('loading' => 'lazy') ); ?>
                </div>
            <?php endif; ?>

            <?php if ($header_image): ?><div class="product-header-wrapper"><?php endif; ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="product-header-info">
                                <h1 class="product-title m-0"><?php the_title(); ?></h1>

                                <?php if ($subtitle): ?>
                                    <div class="product-subtitle h3 m-0"><?php echo esc_html($subtitle); ?></div>
                                <?php endif; ?>

                                <?php if ($product_properties) : ?>
                                    <div class="product-properties-wrapper">
                                        <div class="product-properties">
                                            <?php foreach ($product_properties as $key) : ?>
                                                <span class="icon icon-property-<?php echo esc_html($key['value']); ?>" title="<?php echo esc_html($key['label']); ?>"></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if ($header_image): ?></div><?php endif; ?>
        </div>

        <div class="product-content">
            <?php the_content(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>