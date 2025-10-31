<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

$block_text = get_field('text');

if ($block_text) :
    $header = $block_text['header'];
    $header_type = $block_text['header_type'];
    $subheader = $block_text['subheader'];
    $text = $block_text['text'];
    $button = $block_text['button'];
    $background_image = $block_text['background_image'];
    
    // Set inline style for background image
    $bg_style = '';
    if ($background_image) {
        $bg_style = ' style="background-image: url(' . esc_url($background_image['url']) . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
    }
?>

    <div class="block-text">
        <div class="container">
            <?php if ($header || $subheader || $text ): ?>
                <div class="header-wrapper" <?php echo $bg_style; ?>>
                    <?php if ($header): ?>
                        <div class="header">
                            <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                        </div>
                    <?php endif; ?>

                    <?php if ($subheader): ?>
                        <div class="subheader h3 m-0">
                            <?php echo esc_html($subheader); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($text ): ?>
                        <div class="text text-medium">
                            <?php echo $text; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($button && $button['button_text']): ?>
                        <div class="button-wrapper">
                            <a href="<?php echo esc_url($button['button_link']); ?>" class="btn btn-primary">
                                <?php echo esc_html($button['button_text']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>