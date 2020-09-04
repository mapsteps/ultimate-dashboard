<?php
/**
 * Position metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_position_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_position_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'normal';
	}

	?>

	<div class="neatbox">
		<div class="field radio-field">
			<div class="input-control">
				<ul>
					<li>
						<label>
							<input type="radio" name="udb_metabox_position" value="normal" <?php checked( $saved_meta, 'normal' ); ?> />
							<?php _e( 'Left column', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_position" value="side" <?php checked( $saved_meta, 'side' ); ?> />
							<?php _e( 'Right column', 'ultimate-dashboard' ); ?>
						</label>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php

};
