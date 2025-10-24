<?php
/**
 * Headline text field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );
	$headline = isset( $settings['dashboard_headline'] ) ? $settings['dashboard_headline'] : '';
	?>

	<input type="text" name="udb_settings[dashboard_headline]" class="all-options" value="<?php echo esc_attr( $headline ); ?>" placeholder="<?php esc_attr_e( 'Dashboard', 'ultimate-dashboard' ); ?>" />

	<?php

};
