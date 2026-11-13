<?php
/**
 * Icon color field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );
	$color    = isset( $settings['icon_color'] ) ? $settings['icon_color'] : '';

	// WordPress' own text color is the default. #555555 was the default before 3.9.
	$color = in_array( strtolower( $color ), array( '', '#555555' ), true ) ? '#3c434a' : $color;

	echo '<input type="text" name="udb_settings[icon_color]" value="' . esc_attr( $color ) . '" class="udb-color-field udb-widget-color-settings-field" data-default="#3c434a" />';

};
