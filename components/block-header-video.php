<?php 
wp_enqueue_style('block-header-video', get_template_directory_uri() . '/assets/css/ContentElements/ce-header-video.css', array(), '1.0', 'all');
wp_enqueue_script('block-header-video', get_template_directory_uri() . '/assets/js/ContentElements/ce-header-video.js', array(), '1.0', true);


$header_video = get_field('header_video');

if ($header_video): 
    $media = $header_video['media'];
?>

    <?php if ($media) : ?>
        <div class="block-header-video">
            <video 
                class="video-click-toggle"
                src="<?php echo wp_get_attachment_url($media['ID']); ?>" 
                style="<?= MFP_Video_Style($media['ID']) ?>"
                autoplay muted loop playsinline preload="auto">
                <?php _e('Your browser does not support the video tag.', 'korsch'); ?>
            </video>

            <div class="pause-icon">&#10073;&#10073;</div>

            <div class="scroll-down-icon" onclick="scrollToNextSection(this)">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M40.8333 56.6667L23.3333 38.7191L23.3333 36.3005L40 53.3952L40 21.6667L41.6667 21.6667L41.6667 53.3952L58.3333 36.3021L58.3333 38.7191L40.8333 56.6667Z" fill="white"/>
                    <path d="M58.75 38.8883L40.8333 57.264L22.9167 38.8883L22.9167 35.2767L39.5833 52.3714L39.5833 21.25L42.0833 21.25L42.0833 52.3714L58.75 35.2783L58.75 38.8883ZM41.25 54.4189L41.25 22.0833L40.4167 22.0833L40.4167 54.4189L23.75 37.3242L23.75 38.5498L40.8333 56.0693L57.9167 38.5498L57.9167 37.3258L41.25 54.4189Z" fill="white"/>
                    <path d="M40.8333 56.6667L23.3333 38.7191L23.3333 36.3005L40 53.3952L40 21.6667L41.6667 21.6667L41.6667 53.3952L58.3333 36.3021L58.3333 38.7191L40.8333 56.6667Z" stroke="white" stroke-width="0.1"/>
                    <path d="M58.75 38.8883L40.8333 57.264L22.9167 38.8883L22.9167 35.2767L39.5833 52.3714L39.5833 21.25L42.0833 21.25L42.0833 52.3714L58.75 35.2783L58.75 38.8883ZM41.25 54.4189L41.25 22.0833L40.4167 22.0833L40.4167 54.4189L23.75 37.3242L23.75 38.5498L40.8333 56.0693L57.9167 38.5498L57.9167 37.3258L41.25 54.4189Z" stroke="white" stroke-width="0.1"/>
                </svg>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>