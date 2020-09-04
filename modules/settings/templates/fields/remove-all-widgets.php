<?php
/**
 * Remove all widgets field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove-all'] ) ? absint( $settings['remove-all'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove-all]" class="label checkbox-label">
			<?php _e( 'All', 'ultimate-dashboard' ); ?>
			<input type="checkbox" name="udb_settings[remove-all]" id="udb_settings[remove-all]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
