<?php
/**
 * Headline color field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );
	$color    = isset( $settings['headline_color'] ) ? $settings['headline_color'] : '';

	// WordPress' own headline color is the default. #23282d was the default before 3.9.
	$color = in_array( strtolower( $color ), array( '', '#23282d' ), true ) ? '#1d2327' : $color;

	echo '<input type="text" name="udb_settings[headline_color]" value="' . esc_attr( $color ) . '" class="udb-color-field udb-headline-color-settings-field" data-default="#1d2327" />';

};
