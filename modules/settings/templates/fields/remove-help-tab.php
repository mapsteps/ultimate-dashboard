<?php
/**
 * Remove help tab field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_help_tab'] ) ? absint( $settings['remove_help_tab'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove_help_tab]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove_help_tab]" id="udb_settings[remove_help_tab]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
