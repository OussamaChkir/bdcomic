<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$newsletter = get_field('newsletter');

if ($newsletter) :
    $header = $newsletter['header'];
    $header_type = $newsletter['header_type'];
    $subheader = $newsletter['subheader'];
    $text = $newsletter['text'];
    $script = $newsletter['newsletter_script'];
?>

    <div class="block-text <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
        <div class="container">
            <div class="header-wrapper">
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

                <?php if ($text): ?>
                    <div class="text text-medium">
                        <?php echo $text; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($script): ?>
                    <div class="newsletter-container">
                        <?php echo $script; ?>

                        <button class="btn btn-icon rm-open-popup"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Show modal', 'korsch'); ?></span></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>