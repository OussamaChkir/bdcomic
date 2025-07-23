<?php
wp_enqueue_style('block-accordeon', get_template_directory_uri() . '/assets/css/ContentElements/ce-accordeon.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$accordeon = get_field('accordeon');

if ($accordeon) :
    $header = $accordeon['header'];
    $header_type = $accordeon['header_type'];
    $subheader = $accordeon['subheader'];
    $text = $accordeon['text'];
    $accordeons = $accordeon['accordeons'];
?>

    <div class="block-accordeon <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
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

                    <?php if ($text): ?>
                        <div class="text text-medium">
                            <?php echo $text; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($accordeons) :
                    $accordion_id = 'accordionBox_' . uniqid();
                ?>
                    <div class="accordion" id="<?php echo esc_attr($accordion_id); ?>">
                        <?php foreach ($accordeons as $index => $item): 
                            $title = $item['title'];
                            $type = $item['type'];

                            $textItem = $item['text'];

                            $header = $item['header'];
                            $image = $item['image'];
                            $text_teaser = $item['text_teaser'];
                            $button = $item['button'];

                            $select_product_list = $item['select_product_list'];

                            $tab_show = $item['show_in_anchor_navi'] ?? false;
                            $tab_label = $item['anchor_navi_label'] ?? '';
                            $tab_anchor_id = ($tab_show && $tab_label) ? sanitize_title($tab_label) : '';

                            $collapse_id = $accordion_id . "_collapse_" . $index;
                            $heading_id = $accordion_id . "_heading_" . $index;
                        ?>
                            <div class="accordion-item">
                                <div class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                                    <?php if ($tab_anchor_id): ?>
                                        <div id="<?php echo esc_attr($tab_anchor_id); ?>"></div>
                                    <?php endif; ?>

                                    <button class="accordion-button h3 collapsed" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#<?php echo esc_attr($collapse_id); ?>" 
                                            aria-expanded="false" 
                                            aria-controls="<?php echo esc_attr($collapse_id); ?>">
                                        <?php echo esc_html($title); ?>
                                    </button>
                                </div>
                                <div id="<?php echo esc_attr($collapse_id); ?>" 
                                    class="accordion-collapse collapse" 
                                    aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                                    data-bs-parent="#<?php echo esc_attr($accordion_id); ?>">
                                    <div class="accordion-body content-<?php echo $type; ?>">
                                        <?php if ($type == "text"): ?>
                                            <?php if ($textItem): ?>
                                                <div class="text text-medium">
                                                    <?php echo $textItem; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($type == "teaser"): ?>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <?php if ($image) : ?>
                                                        <div class="image">
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'bild-teaser', false, array('loading' => 'lazy') ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="teaser">
                                                        <?php if ($header): ?>
                                                            <div class="title h3 m-0"><b><?php echo esc_html($header); ?></b></div>
                                                        <?php endif; ?>

                                                        <?php if ($text_teaser): ?>
                                                            <div class="text text-medium"><?php echo $text_teaser; ?></div>
                                                        <?php endif; ?>

                                                        <?php if ($button) : ?>
                                                            <a class="btn btn-icon" href="<?php echo $button["url"]; ?>" target="<?php echo $button["target"]; ?>"><span class="icon icon-arrow-right"></span><span class="label"><?php echo $button["title"]; ?></span></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php elseif ($type == "product-list"): ?>
                                            <?php if (!empty($select_product_list)) :
                                                wp_enqueue_style('accordeon-product-list', get_template_directory_uri() . '/assets/css/ContentElements/ce-accordeon-product-list.css', array(), '1.0', 'all');
                                                wp_enqueue_script('accordeon-product-list', get_template_directory_uri() . '/assets/js/ContentElements/ce-accordeon-product-list.js', array(), '1.0', true);    
                                            ?>
                                                <div class="product-list row">
                                                    <?php foreach ($select_product_list as $product) :
                                                        setup_postdata($product);

                                                        $product_id = $product->ID;
                                                        $title = get_the_title($product_id);
                                                        $permalink = get_permalink($product_id);
                                                        $thumbnail = get_the_post_thumbnail($product_id, 'image-list');

                                                        $subtitle = get_field('subtitle', $product_id);
                                                        $header_image = get_field('header_image', $product_id);
                                                        $teaser_text = get_field('teaser_text', $product_id);
                                                        $product_properties = get_field('product_properties', $product_id);
                                                        $technical_details = get_field('technical_details', $product_id);
                                                    ?>
                                                        <div class="col-lg-6">
                                                            <div class="product-card">
                                                                <div class="front primary-bg">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <div class="product-title h3 m-0"><b><?php echo esc_html($title); ?></b></div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <?php if ($subtitle) : ?>
                                                                                <div class="product-subtitle h6 m-0"><b><?php echo esc_html($subtitle); ?></b></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-image-wrapper">
                                                                        <?php if ($product_properties) : ?>
                                                                            <div class="product-properties">
                                                                                <?php foreach ($product_properties as $key) : ?>
                                                                                    <span class="icon-property-<?php echo esc_html($key['value']); ?>" title="<?php echo esc_html($key['label']); ?>"></span>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <?php if ($thumbnail) : ?>
                                                                            <div class="product-image">
                                                                                <?php echo $thumbnail; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <a class="btn btn-icon btn-turn-back" href="#"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Quick view', 'korsch'); ?></span></a>
                                                                </div>

                                                                <div class="back secondary-bg">
                                                                    <div class="product-content">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-6">
                                                                                <div class="product-title h3 m-0"><b><?php echo esc_html($title); ?></b></div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <a class="btn-turn-front" href="#"><span class="icon-turn"></span></a>
                                                                            </div>
                                                                        </div>

                                                                        <?php if ($teaser_text) : ?>
                                                                            <div class="product-text h6 m-0"><?php echo esc_html($teaser_text); ?></div>
                                                                        <?php endif; ?>

                                                                        <?php if ($technical_details) : ?>
                                                                            <table class="table table-bordered h6">
                                                                                <tbody>
                                                                                    <?php $field_group = acf_get_field('field_686f82ae3648b');

                                                                                    foreach ($technical_details as $sub_key => $value) :
                                                                                        if (!empty($value)) :
                                                                                            $label = '';
                                                                                            if (!empty($field_group['sub_fields'])) {
                                                                                                foreach ($field_group['sub_fields'] as $sub_field) {
                                                                                                    if ($sub_field['name'] === $sub_key) {
                                                                                                        $label = $sub_field['label'];
                                                                                                        break;
                                                                                                    }
                                                                                                }
                                                                                            } ?>
                                                                                            <tr>
                                                                                                <th scope="row"><?php echo $label; ?></th>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <a href="<?php echo esc_url($permalink); ?>" class="btn btn-icon"><span class="icon icon-arrow-right"></span><span class="label"><?php _e('Full view', 'korsch'); ?></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <?php wp_reset_postdata(); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>