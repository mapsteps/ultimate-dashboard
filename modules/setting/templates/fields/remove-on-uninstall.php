<?php
/**
 * Remove on uninstall field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove-on-uninstall'] ) ? 1 : 0;
	?>

	<label for="udb_settings[remove-on-uninstall]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="udb_settings[remove-on-uninstall]" id="udb_settings[remove-on-uninstall]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<p class="description">
		<?php esc_html_e( 'Deletes all Ultimate Dashboard settings, dashboard widgets and admin pages when you delete the plugin. Deactivating it keeps everything.', 'ultimate-dashboard' ); ?>
	</p>

	<?php

};
