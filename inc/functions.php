<?php 
/**
 * Add automatic image sizes
 */
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'header-image', 2000, 1000, false );
	add_image_size( 'product-image', 1200, 1000, false );
	add_image_size( 'image-teaser', 300, 400, false );
	add_image_size( 'post-teaser', 600, 600, false );
	add_image_size( 'bild-teaser', 800, 600, false );
	add_image_size( 'image-list', 800, 600, false );
	add_image_size( 'prefooter-image', 2000, 1500, false );
	add_image_size( 'logo-wall', 400, 200, false );
}
?>