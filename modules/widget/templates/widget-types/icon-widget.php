<?php
/**
 * Icon widget.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	$icon        = get_post_meta( $post->ID, 'udb_icon_key', true );
	$icon        = $icon ? $icon : 'dashicons dashicons-menu';
	$tooltip     = get_post_meta( $post->ID, 'udb_tooltip', true );
	$link        = get_post_meta( $post->ID, 'udb_link', true );
	$link_target = get_post_meta( $post->ID, 'udb_link_target', true );
	?>

	<div data-type="icon">
		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Icon Widget', 'ultimate-dashboard' ); ?></h2>
			</div>
			<div class="inside">

				<div class="udb-metabox-field udb-icon-row">
					<div>
						<label class="udb-field-label" for="udb_icon_button"><?php esc_html_e( 'Icon', 'ultimate-dashboard' ); ?></label>
						<div class="udb-icon-field">
							<button type="button" class="icon-picker" id="udb_icon_button" aria-label="<?php esc_attr_e( 'Change icon', 'ultimate-dashboard' ); ?>">
								<i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
							</button>
							<input type="hidden" name="udb_icon" id="udb_icon" value="<?php echo esc_attr( $icon ); ?>">
						</div>
					</div>
					<div class="udb-icon-row-tooltip">
						<label class="udb-field-label" for="udb-tooltip"><?php esc_html_e( 'Tooltip', 'ultimate-dashboard' ); ?></label>
						<textarea class="widefat" id="udb-tooltip" name="udb_tooltip"><?php echo esc_textarea( $tooltip ? $tooltip : '' ); ?></textarea>
					</div>
				</div>

				<div class="udb-metabox-field">
					<label class="udb-field-label" for="udb_link"><?php esc_html_e( 'Link', 'ultimate-dashboard' ); ?></label>
					<input id="udb_link" type="text" name="udb_link" value="<?php echo esc_attr( $link ? $link : '' ); ?>">
					<p class="description"><?php esc_html_e( "Absolute URL's (incl. http:// or https://) or relative URL's (./post-new.php) are allowed.", 'ultimate-dashboard' ); ?></p>
					<p>
						<label>
							<input id="udb_link_target" type="checkbox" name="udb_link_target" <?php checked( $link_target, '_blank' ); ?>>
							<?php esc_html_e( 'Open link in a new tab.', 'ultimate-dashboard' ); ?>
						</label>
					</p>
				</div>

			</div>
		</div>
	</div>

	<?php

};
