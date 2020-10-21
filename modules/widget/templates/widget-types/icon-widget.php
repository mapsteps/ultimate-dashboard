<?php
/**
 * Icon widget.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	?>

	<div data-type="icon">

		<div class="subbox">
			<h2><?php _e( 'Icon', 'utimate-dashboard' ); ?></h2>

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

			<div class="field">
				<div class="input-control">
					<div class="icon-preview"></div>
				</div>
			</div>
			<div class="field">
				<div class="label-control">
					<label for="udb_icon"><?php _e( 'Select Icon', 'ultimate-dashboard' ); ?></label>
				</div>
				<div class="input-control">
					<input type="text" class="icon-picker" data-width="100%" name="udb_icon" id="udb_icon" value="<?php echo esc_attr( $stored_meta ? $stored_meta : 'dashicons dashicons-menu' ); ?>" placeholder="dashicons dashicons-menu" />
				</div>
			</div>
		</div>

		<div class="subbox">
			<h2><?php _e( 'Tooltip', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_tooltip', true );
				?>
				<div class="input-control">
					<textarea style="width: 100%; height: 100px;" id="udb-tooltip" name="udb_tooltip"><?php echo esc_html( $stored_meta ? $stored_meta : '' ); ?></textarea>
				</div>
			</div>
		</div>

		<div class="subbox">
			<h2><?php _e( 'Link', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_link', true );
				?>
				<div class="label-control">
					<p class="description"><?php _e( "Absolute URL's (incl. http:// or https://) or relative URL's (./post-new.php) are allowed.", 'ultimate-dashboard' ); ?></p>
				</div>
				<div class="input-control">
					<input id="udb_link" type="text" name="udb_link" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
				</div>
			</div>

			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_link_target', true );
				?>
				<div class="label-control">
					<label for="udb_link_target"><?php _e( 'Target', 'ultimate-dashboard' ); ?></label>
				</div>
				<div class="input-control">
					<label>
						<input id="udb_link_target" type="checkbox" name="udb_link_target" <?php checked( $stored_meta, '_blank' ); ?>>
						<span class="message"><?php _e( 'Open link in a new tab.', 'ultimate-dashboard' ); ?></span>
					</label>
				</div>
			</div>
		</div>

	</div>

	<?php

};
