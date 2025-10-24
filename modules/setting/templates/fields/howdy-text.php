<?php
/**
 * Howdy text field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );
	$headline = isset( $settings['howdy_text'] ) ? $settings['howdy_text'] : '';
	?>

	<input type="text" name="udb_settings[howdy_text]" class="all-options" value="<?php echo esc_attr( $headline ); ?>" placeholder="<?php esc_attr_e( 'Howdy', 'ultimate-dashboard' ); ?>" />

	<?php

};
