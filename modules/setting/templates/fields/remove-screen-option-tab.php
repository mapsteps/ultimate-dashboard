<?php
/**
 * Remove screen option tab field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_screen_options'] ) ? 1 : 0;
	?>

	<label for="udb_settings[remove_screen_options]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="udb_settings[remove_screen_options]" id="udb_settings[remove_screen_options]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<?php

};
