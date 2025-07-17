<?php
wp_enqueue_style('block-product-details-table', get_template_directory_uri() . '/assets/css/ContentElements/ce-product-details-table.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$product_details_table = get_field('product_details_table');

if ($product_details_table) :
    $header = $product_details_table['header'];
    $header_type = $product_details_table['header_type'];
    $subheader = $product_details_table['subheader'];
    $text = $product_details_table['text'];


    $product = get_post();
    $product_id = $product->ID;

    $product_properties = get_field('product_properties', $product_id);
    $technical_details = get_field('technical_details', $product_id);
?>

    <div class="block-product-details-table <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
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

                <?php if ($product_properties || $technical_details): ?>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="table-responsive">
                                <table class="table table-bordered h6">
                                    <tbody>
                                        <?php if ($product_properties) : ?>
                                            <tr>
                                                <th scope="row"><?php _e('Compatible with', 'korsch'); ?></th>
                                                <td>
                                                    <div class="product-properties-label">
                                                        <?php foreach ($product_properties as $key) : ?>
                                                            <span><?php echo esc_html($key['label']); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="product-properties-wrapper">
                                                        <div class="product-properties">
                                                            <?php foreach ($product_properties as $key) : ?>
                                                                <span class="icon-property-<?php echo esc_html($key['value']); ?>" title="<?php echo esc_html($key['label']); ?>"></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?> 
                                        
                                        <?php if ($technical_details) :
                                            $field_group = acf_get_field('field_686f82ae3648b');

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
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>