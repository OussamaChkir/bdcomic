<?php 
/**
 * Add automatic image sizes
 */
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'image-teaser', 276, 395, false );
	add_image_size( 'post-teaser', 400, 400, false );
}
?>