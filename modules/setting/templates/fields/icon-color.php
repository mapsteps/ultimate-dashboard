<?php
/**
 * Icon color field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings     = get_option( 'udb_settings' );
	$accent_color = isset( $settings['icon_color'] ) ? $settings['icon_color'] : '#555555';

	echo '<input type="text" name="udb_settings[icon_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-field udb-widget-color-settings-field" data-default="#555555" />';

};
