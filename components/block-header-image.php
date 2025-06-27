<?php 
wp_enqueue_style('block-header-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-header-image.css', array(), '1.0', 'all');


$header_image = get_field('header_image');

if ($header_image): 
    $media = $header_image['media'];
?>

    <?php if ($media) : ?>
        <div class="block-header-image">
            <?php echo wp_get_attachment_image( $media['ID'], 'header-image', false, array('loading' => 'lazy') ); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>