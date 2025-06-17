<?php 
wp_enqueue_style('block-header-video', get_template_directory_uri() . '/assets/css/ContentElements/ce-header-video.css', array(), '1.0', 'all');


$header_video = get_field('header_video');

if ($header_video): 
    $media = $header_video['media'];
?>

    <?php if ($media) : ?>
        <div class="block-header-video">
            <video 
                src="<?php echo wp_get_attachment_url($media['ID']); ?>" 
                style="<?= MFP_Video_Style($media['ID']) ?>"
                autoplay muted loop playsinline>
                <?php _e('Your browser does not support the video tag.', 'korsch'); ?>
            </video>
        </div>
    <?php endif; ?>
<?php endif; ?>