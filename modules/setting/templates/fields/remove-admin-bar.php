<?php
/**
 * Remove admin bar field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_admin_bar'] ) ? 1 : 0;
	?>

	<label for="udb_settings[remove_admin_bar]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="udb_settings[remove_admin_bar]" id="udb_settings[remove_admin_bar]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<?php

};
