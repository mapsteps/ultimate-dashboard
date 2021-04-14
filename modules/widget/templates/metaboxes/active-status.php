<?php
/**
 * Active status metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$is_active = (int) get_post_meta( $post->ID, 'udb_is_active', true );

	global $current_screen;

	// Set default to 1 if we're adding a new widget.
	if ( 'add' === $current_screen->action ) {
		$is_active = 1;
	}

	?>

	<?php wp_nonce_field( 'udb_widget_active', 'udb_widget_active_nonce' ); ?>
	<div class="switch-control is-rounded is-small">
		<label for="udb_is_active">
			<input type="checkbox" name="udb_is_active" id="udb_is_active" value="1" <?php checked( $is_active, 1 ); ?>>
			<span class="switch"></span>
		</label>
	</div>

	<?php

};
