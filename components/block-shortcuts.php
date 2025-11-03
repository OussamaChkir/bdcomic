<?php
$shortcode = get_field('shortcode');

if (!empty($shortcode)) :
?>
	<div class="block-shortcuts">
		<div class="container">
			<?php echo do_shortcode($shortcode); ?>
		</div>
	</div>
<?php endif; ?>


