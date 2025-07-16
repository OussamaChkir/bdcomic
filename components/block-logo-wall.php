<?php
wp_enqueue_style('block-logo-wall', get_template_directory_uri() . '/assets/css/ContentElements/ce-logo-wall.css', array(), '1.0', 'all');
wp_enqueue_script('block-logo-wall', get_template_directory_uri() . '/assets/js/ContentElements/ce-logo-wall.js', array(), '1.0', true);

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$logo_wall = get_field('logo_wall');

if ($logo_wall) :
    $header = $logo_wall['header'];
    $header_type = $logo_wall['header_type'];
    $subheader = $logo_wall['subheader'];
    $text = $logo_wall['text'];
    $logos = $logo_wall['logos'];
?>

    <div class="block-logo-wall <?php echo $background_color; ?>">
        <div class="container">
            <div class="block-container">
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
                </div>

                <?php if ($logos): ?>
                    <div class="logos-container">
                        <div class="row">
                            <?php foreach ($logos as $index => $logo): ?>
                                <div class="col-lg-3 col-6 <?php echo $index >= 4 ? 'hidden-logo' : ''; ?>">
                                    <div class="logo-image">
                                        <?php echo wp_get_attachment_image($logo['ID'], 'logo-wall', false, array('loading' => 'lazy')); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if (count($logos) > 4): ?>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-icon load-more-logos" href="#"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Load more', 'korsch'); ?></span></a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($text): ?>
                    <div class="text text-medium">
                        <?php echo $text; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>