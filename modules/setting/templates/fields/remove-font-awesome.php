<?php
/**
 * Remove font awesome field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_font_awesome'] ) ? 1 : 0;
	?>

	<label for="udb_settings[remove_font_awesome]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="udb_settings[remove_font_awesome]" id="udb_settings[remove_font_awesome]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<?php

};
