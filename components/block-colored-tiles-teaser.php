<?php
if ( ! wp_style_is( 'modal', 'enqueued' ) ) {
    wp_enqueue_style('modal', get_template_directory_uri() . '/assets/css/Globals/modal.css', array(), '1.0', 'all');
}

wp_enqueue_style('block-colored-tiles-teaser', get_template_directory_uri() . '/assets/css/ContentElements/ce-colored-tiles-teaser.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$colored_tiles_teaser = get_field('colored_tiles_teaser');

if ($colored_tiles_teaser) :
    $header = $colored_tiles_teaser['header'];
    $header_type = $colored_tiles_teaser['header_type'];
    $subheader = $colored_tiles_teaser['subheader'];
    $text = $colored_tiles_teaser['text'];
    $tiles = $colored_tiles_teaser['tiles'];
?>

    <div class="block-colored-tiles-teaser <?php echo $background_color; ?>">
        <div class="container">
            <div class="block-container">
                <?php if ($header || $subheader || $text || $second_text): ?>
                    <div class="header-wrapper">
                        <?php if ($header): ?>
                            <div class="header">
                                <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                            </div>
                        <?php endif; ?>

                        <?php if ($subheader): ?>
                            <div class="subheader h3">
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

                <?php if ($tiles) : ?>
                    <div class="tiles-container">
                        <div class="row">
                            <?php foreach ($tiles as $index => $item):
                                $title = $item['title'];
                                $color = $item['color'];
                                $textItem = $item['text'];
                                $modal_id = 'tileModal_' . $index;
                            ?>

                                <div class="col-md-6">
                                    <div class="tiles <?php echo $color; ?>-bg text-white">
                                        <?php if ($title): ?>
                                            <div class="title h2">
                                                <b><?php echo esc_html($title); ?></b>
                                            </div>
                                        <?php endif; ?>

                                        <button
                                            type="button"
                                            class="icon icon-plus"
                                            data-bs-toggle="modal"
                                            data-bs-target="#<?php echo $modal_id; ?>">
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modal_id; ?>Label" aria-hidden="true">
                                    <div class="modal-dialog container animate-slide-up" role="document">
                                        <div class="modal-content <?php echo $color; ?>-bg text-white">
                                            <div class="header-wrapper">
                                                <div class="modal-header">
                                                    <div class="modal-title h2" id="<?php echo $modal_id; ?>Label">
                                                        <b><?php echo esc_html($title); ?></b>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if ($textItem): ?>
                                                        <div class="text text-medium">
                                                            <?php echo $textItem; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
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