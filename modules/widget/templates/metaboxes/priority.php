<?php
/**
 * Priority metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	wp_nonce_field( 'udb_priority', 'udb_priority_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_priority_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'default';
	}

	?>

	<ul>
		<li>
			<label>
				<input type="radio" name="udb_metabox_priority" value="default" <?php checked( $saved_meta, 'default' ); ?> />
				<?php esc_html_e( 'Default', 'ultimate-dashboard' ); ?>
			</label>
		</li>
		<li>
			<label>
				<input type="radio" name="udb_metabox_priority" value="low" <?php checked( $saved_meta, 'low' ); ?> />
				<?php esc_html_e( 'Low', 'ultimate-dashboard' ); ?>
			</label>
		</li>
		<li>
			<label>
				<input type="radio" name="udb_metabox_priority" value="high" <?php checked( $saved_meta, 'high' ); ?> />
				<?php esc_html_e( 'High', 'ultimate-dashboard' ); ?>
			</label>
		</li>
	</ul>

	<?php

};
