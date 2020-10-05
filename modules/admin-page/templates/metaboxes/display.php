<?php
/**
 * Display options metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$remove_page_title    = (int) get_post_meta( $post->ID, 'udb_remove_page_title', true );
	$remove_page_margin   = (int) get_post_meta( $post->ID, 'udb_remove_page_margin', true );
	$remove_admin_notices = (int) get_post_meta( $post->ID, 'udb_remove_admin_notices', true );

	?>

	<div class="postbox-content has-lines">
		<div class="fields">
			<div class="field">
				<div class="control">
					<label for="udb_remove_page_title" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_page_title" id="udb_remove_page_title" value="1" <?php checked( $remove_page_title, 1 ); ?>>
						<?php _e( 'Remove Page Title', 'ultimate-dashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the page title from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
					</p>
				</div>
			</div>

			<div class="field">
				<div class="control">
					<label for="udb_remove_page_margin" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_page_margin" id="udb_remove_page_margin" value="1" <?php checked( $remove_page_margin, 1 ); ?>>
						<?php _e( 'Remove Page Margin', 'ultimate-dashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the default margins from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
					</p>
				</div>
			</div>

			<div class="field">
				<div class="control">
					<label for="udb_remove_admin_notices" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_admin_notices" id="udb_remove_admin_notices" value="1" <?php checked( $remove_admin_notices, 1 ); ?>>
						<?php _e( 'Remove Admin Notices', 'ultimate-dashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the admin notices (if any) from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<?php

};
