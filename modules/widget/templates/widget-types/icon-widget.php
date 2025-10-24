<?php
/**
 * Icon widget.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	?>

	<div data-type="icon">

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Icon', 'ultimate-dashboard' ); ?></h2>
			</div>

			<?php

			$stored_meta = get_post_meta( $post->ID, 'udb_icon_key', true );
			$dashicons   = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/dashicons.json' );
			$dashicons   = json_decode( $dashicons, true );
			$dashicons   = $dashicons ? $dashicons : array();
			$fontawesome = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/fontawesome5.json' );
			$fontawesome = json_decode( $fontawesome, true );
			$fontawesome = $fontawesome ? $fontawesome : array();
			$udb_icons   = array_merge( $dashicons, $fontawesome );

			wp_localize_script(
				'udb-edit-widget',
				'iconPickerIcons',
				$udb_icons
			);

			?>

			<div class="inside">
				<div class="udb-metabox-field">
					<div class="icon-preview"></div>
				</div>
				<div class="udb-metabox-field">
					<label for="udb_icon"><?php esc_html_e( 'Select Icon', 'ultimate-dashboard' ); ?></label>
					<input type="text" class="icon-picker" data-width="100%" name="udb_icon" id="udb_icon" value="<?php echo esc_attr( $stored_meta ? $stored_meta : 'dashicons dashicons-menu' ); ?>" placeholder="dashicons dashicons-menu" />
				</div>
			</div>
		</div>

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Tooltip', 'ultimate-dashboard' ); ?></h2>
			</div>
			<div class="inside">
				<?php $stored_meta = get_post_meta( $post->ID, 'udb_tooltip', true ); ?>
				<textarea style="width: 100%; height: 100px;" id="udb-tooltip" name="udb_tooltip"><?php echo esc_html( $stored_meta ? $stored_meta : '' ); ?></textarea>
			</div>
		</div>

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Link', 'ultimate-dashboard' ); ?></h2>
			</div>
			<div class="inside">
				<div class="udb-metabox-field">
					<?php $stored_meta = get_post_meta( $post->ID, 'udb_link', true ); ?>
					<input id="udb_link" type="text" name="udb_link" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
					<p class="description"><?php esc_html_e( "Absolute URL's (incl. http:// or https://) or relative URL's (./post-new.php) are allowed.", 'ultimate-dashboard' ); ?></p>
				</div>
				<?php $stored_meta = get_post_meta( $post->ID, 'udb_link_target', true ); ?>
				<label>
					<input id="udb_link_target" type="checkbox" name="udb_link_target" <?php checked( $stored_meta, '_blank' ); ?>>
					<?php esc_html_e( 'Open link in a new tab.', 'ultimate-dashboard' ); ?>
				</label>
			</div>
		</div>

	</div>

	<?php

};
