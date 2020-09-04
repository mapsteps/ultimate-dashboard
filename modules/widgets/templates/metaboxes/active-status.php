<?php
/**
 * Active status metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$is_active = (int) get_post_meta( $post->ID, 'udb_is_active', true );

	global $current_screen;

	// If this is adding a new post.
	if ( 'add' === $current_screen->action ) {
		$is_active = 1;
	}

	?>

	<div class="postbox-content">
		<?php wp_nonce_field( 'udb_edit_admin_page', 'udb_widget_active_nonce' ); ?>
		<div class="fields">
			<div class="field">
				<div class="control switch-control is-rounded is-small">
					<label for="udb_is_active">
						<input type="checkbox" name="udb_is_active" id="udb_is_active" value="1" <?php checked( $is_active, 1 ); ?>>
						<span class="switch"></span>
					</label>
				</div>
			</div>
		</div>
	</div>

	<?php

};
