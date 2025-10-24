<?php
/**
 * Position metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	wp_nonce_field( 'udb_position', 'udb_position_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_position_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'normal';
	}

	?>

	<ul>
		<li>
			<label>
				<input type="radio" name="udb_metabox_position" value="normal" <?php checked( $saved_meta, 'normal' ); ?> />
				<?php esc_html_e( 'Left column', 'ultimate-dashboard' ); ?>
			</label>
		</li>
		<li>
			<label>
				<input type="radio" name="udb_metabox_position" value="side" <?php checked( $saved_meta, 'side' ); ?> />
				<?php esc_html_e( 'Right column', 'ultimate-dashboard' ); ?>
			</label>
		</li>
	</ul>

	<?php

};
