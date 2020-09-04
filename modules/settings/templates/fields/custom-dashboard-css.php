<?php
/**
 * Custom dashboard css field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['custom_css'] ) ) {
		$custom_css = false;
	} else {
		$custom_css = $settings['custom_css'];
	}

	?>

	<textarea id="udb-custom-dashboard-css" class="widefat textarea udb-custom-css" name="udb_settings[custom_css]"><?php echo wp_unslash( $custom_css ); ?></textarea>

	<?php

};
