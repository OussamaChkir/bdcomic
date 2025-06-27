<?php

wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

$block_text = get_field('text');

if ($block_text) :
    $header = $block_text['header'];
    $header_type = $block_text['header_type'];
    $subheader = $block_text['subheader'];
    $text = $block_text['text'];
    $second_text = $block_text['second_text'];
?>

    <div class="block-text">
        <div class="container">
            <?php if ($header || $subheader || $text || $second_text): ?>
                <div class="header-wrapper">
                    <?php if ($header): ?>
                        <div class="header">
                            <?php if ( is_front_page() ) : ?>
                                <<?php echo $header_type; ?> class="h1"><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
                            <?php else : ?>
                                <<?php echo $header_type; ?>><?php echo esc_html($header); ?></<?php echo $header_type; ?>>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($subheader): ?>
                        <div class="subheader h3">
                            <?php echo esc_html($subheader); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($text && $second_text): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text text-medium">
                                    <?php echo $text; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="second_text text-medium">
                                    <?php echo $second_text; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($text): ?>
                        <div class="text text-medium">
                            <?php echo $text; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>