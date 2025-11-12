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
                    $sous_titre = $icon_text['sous_titre'];
                    $text = $icon_text['text'];
                ?>
                    <div class="block-icon-text-item">
                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
                        <?php if ($sous_titre) : ?>
                            <h3 class="block-icon-text-sous-titre"><?php echo esc_html($sous_titre); ?></h3>
                        <?php endif; ?>
                        <p class="block-icon-text-text"><?php echo esc_html($text); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>