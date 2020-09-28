<?php
/**
 * Advanced metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$custom_css = get_post_meta( $post->ID, 'udb_custom_css', true );
	?>

	<div class="postbox-content has-lines">
		<div class="fields">

			<div class="field">
				<label class="label" for="udb_custom_css">
					<?php _e( 'Custom CSS', 'ultimatedashboard' ); ?>
				</label>
				<p class="description">
					<?php _e( 'Add Custom CSS to the Custom Admin Page.', 'ultimatedashboard' ); ?>
				</p>
				<div class="control">
					<textarea id="udb_custom_css" class="widefat textarea udb-custom-css" name="udb_custom_css"><?php echo wp_unslash( $custom_css ); ?></textarea>
				</div>
			</div>

			<?php do_action( 'udb_admin_page_advanced_fields', $post ); ?>

		</div>
	</div>

	<?php

};
