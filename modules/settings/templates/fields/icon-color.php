<?php
/**
 * Icon color field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['icon_color'] ) ) {
		$accent_color = 0;
	} else {
		$accent_color = $settings['icon_color'];
	}

	echo '<input type="text" name="udb_settings[icon_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-field udb-widget-color-settings-field" data-default="#555555" />';

};
