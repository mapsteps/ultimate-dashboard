<?php
/**
 * Advanced metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$custom_css = get_post_meta( $post->ID, 'udb_custom_css', true );
	?>

	<h4><?php _e( 'Custom CSS', 'ultimate-dashboard' ); ?></h4>
	<textarea id="udb_custom_css" class="widefat textarea udb-custom-css" name="udb_custom_css"><?php echo wp_unslash( $custom_css ); ?></textarea>

	<?php

	do_action( 'udb_admin_page_advanced_fields', $post );

};
