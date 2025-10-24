<?php
/**
 * Remove all widgets field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove-all'] ) ? 1 : 0;
	?>

	<label for="udb_settings[remove-all]" class="label checkbox-label">
		<?php esc_html_e( 'All', 'ultimate-dashboard' ); ?>
		<input type="checkbox" name="udb_settings[remove-all]" id="udb_settings[remove-all]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<?php

};
