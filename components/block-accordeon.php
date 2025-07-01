<?php

wp_enqueue_style('block-accordeon', get_template_directory_uri() . '/assets/css/ContentElements/ce-accordeon.css', array(), '1.0', 'all');

$accordeon = get_field('accordeon');

if ($accordeon) :
    $header = $accordeon['header'];
    $header_type = $accordeon['header_type'];
    $subheader = $accordeon['subheader'];
    $text = $accordeon['text'];
    $accordeons = $accordeon['accordeons'];
?>

    <div class="block-accordeon">
        <div class="container">
            <div class="block-container">
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

                <?php if ($accordeons) : ?>
                    <div class="accordion" id="accordionBox">
                        <?php foreach ($accordeons as $index => $item): 
                            $title = $item['title'];
                            $type = $item['type'];

                            $textItem = $item['text'];

                            $header = $item['header'];
                            $image = $item['image'];
                            $text_teaser = $item['text_teaser'];
                            $button = $item['button'];

                            $select_product_list = $item['select_product_list'];

                            $collapse_id = "collapse" . $index;
                            $heading_id = "heading" . $index;
                        ?>
                            <div class="accordion-item">
                                <div class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                                    <button class="accordion-button h3 <?php echo $index > 0 ? 'collapsed' : ''; ?>" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#<?php echo esc_attr($collapse_id); ?>" 
                                            aria-expanded="<?php echo $index == 0 ? 'true' : 'false'; ?>" 
                                            aria-controls="<?php echo esc_attr($collapse_id); ?>">
                                        <?php echo esc_html($title); ?>
                                    </button>
                                </div>
                                <div id="<?php echo esc_attr($collapse_id); ?>" 
                                    class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" 
                                    aria-labelledby="<?php echo esc_attr($heading_id); ?>">
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