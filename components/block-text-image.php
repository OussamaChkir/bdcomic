<?php
wp_enqueue_style('block-text-image', get_template_directory_uri() . '/assets/css/ContentElements/ce-text-image.css', array(), '1.0', 'all');

$data = get_field('text_image');

if ($data) :
	$title = isset($data['title']) ? $data['title'] : '';
	$content = isset($data['content']) ? $data['content'] : '';
	$image = isset($data['image']) ? $data['image'] : null;
	$reverse = !empty($data['reverse_layout']);
?>

	<div class="block-text-image<?php echo $reverse ? ' is-reversed' : ''; ?>">
		<div class="container">
			<div class="block-text-image__inner">
				<?php if ($image) : ?>
					<div class="block-text-image__media">
						<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
					</div>
				<?php endif; ?>

				<div class="block-text-image__content">
					<?php if ($title) : ?>
						<h2 class="block-text-image__title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<?php if ($content) : ?>
						<div class="block-text-image__text text-medium">
							<?php echo $content; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


