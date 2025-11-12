<?php
wp_enqueue_style('block-icon-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-icon-text.css', array(), '1.0', 'all');
$title = get_field('title');
$block_icon_text = get_field('icon_text');

if ($block_icon_text) : ?>
    <div class="block-icon-text">
        <div class="container">
            <?php if ($title) : ?>
                <h2 class="block-icon-text-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <div class="block-icon-text-row">
                <?php foreach ($block_icon_text as $icon_text) :
                    $icon = $icon_text['icon'];
                    $text = $icon_text['text'];
                ?>
                    <div class="block-icon-text-item">
                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
                        <div class="block-icon-text-text"><?php echo esc_html($text); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>