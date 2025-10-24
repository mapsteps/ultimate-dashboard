<?php
/**
 * Display options metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$remove_page_title    = (int) get_post_meta( $post->ID, 'udb_remove_page_title', true );
	$remove_page_margin   = (int) get_post_meta( $post->ID, 'udb_remove_page_margin', true );
	$remove_admin_notices = (int) get_post_meta( $post->ID, 'udb_remove_admin_notices', true );

	?>

	<div class="udb-metabox-field">
		<label for="udb_remove_page_title" class="label checkbox-label">
			<input type="checkbox" name="udb_remove_page_title" id="udb_remove_page_title" value="1" <?php checked( $remove_page_title, 1 ); ?>>
			<strong><?php esc_html_e( 'Remove Page Title', 'ultimate-dashboard' ); ?></strong>
		</label>
		<p class="description">
			<?php esc_html_e( 'Remove the page title from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
		</p>
	</div>

	<div class="udb-metabox-field">
		<label for="udb_remove_page_margin" class="label checkbox-label">
			<input type="checkbox" name="udb_remove_page_margin" id="udb_remove_page_margin" value="1" <?php checked( $remove_page_margin, 1 ); ?>>
			<strong><?php esc_html_e( 'Remove Page Margin', 'ultimate-dashboard' ); ?></strong>
		</label>
		<p class="description">
			<?php esc_html_e( 'Remove the default margins from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
		</p>
	</div>

	<div class="udb-metabox-field">
		<label for="udb_remove_admin_notices" class="label checkbox-label">
			<input type="checkbox" name="udb_remove_admin_notices" id="udb_remove_admin_notices" value="1" <?php checked( $remove_admin_notices, 1 ); ?>>
			<strong><?php esc_html_e( 'Remove Admin Notices', 'ultimate-dashboard' ); ?></strong>
		</label>
		<p class="description">
			<?php esc_html_e( 'Remove the admin notices (if any) from the Custom Admin Page.', 'ultimate-dashboard' ); ?>
		</p>
	</div>

	<?php

};
