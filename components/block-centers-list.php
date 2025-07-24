<?php
wp_enqueue_style('block-centers-list', get_template_directory_uri() . '/assets/css/ContentElements/ce-centers-list.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$centers_list = get_field('centers_list');

if ($centers_list) :
    $header = $centers_list['header'];
    $header_type = $centers_list['header_type'];
    $subheader = $centers_list['subheader'];
    $text = $centers_list['text'];
    $centers = $centers_list['centers'];
?>

    <div class="block-centers-list <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
        <div class="container">
            <div class="block-container">
                <?php if ($header || $subheader || $text): ?>
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
                    </div>
                <?php endif; ?>

                <?php if ($centers) : ?>
                    <div class="centers-container">
                        <div class="row">
                            <?php foreach ($centers as $item) :
                                $title = $item['title'];
                                $location = $item['location'];
                                $email = $item['email'];
                                $phone = $item['phone'];
                            ?>
                                <div class="col-xl-4 col-md-6">
                                    <div class="center-teaser h6">
                                        <div class="location">
                                            <?php if ($title): ?><div><b><?php echo esc_html($title); ?></b></div><?php endif; ?>
                                            <?php if ($location): ?><div><?php echo esc_html($location); ?></div><?php endif; ?>
                                        </div>
                                        <div class="gap-8">
                                            <?php if ($email): ?><div><span class="icon-email"></span><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div><?php endif; ?>
                                            <?php if ($phone): ?><div><span class="icon-mobile"></span><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></div><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>