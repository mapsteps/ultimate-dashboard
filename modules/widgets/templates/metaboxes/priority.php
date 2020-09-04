<?php
/**
 * Priority metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_priority_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_priority_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'default';
	}

	?>

	<div class="neatbox">
		<div class="field radio-field">
			<div class="input-control">
				<ul>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="default" <?php checked( $saved_meta, 'default' ); ?> />
							<?php _e( 'Default', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="low" <?php checked( $saved_meta, 'low' ); ?> />
							<?php _e( 'Low', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="high" <?php checked( $saved_meta, 'high' ); ?> />
							<?php _e( 'High', 'ultimate-dashboard' ); ?>
						</label>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php

};
