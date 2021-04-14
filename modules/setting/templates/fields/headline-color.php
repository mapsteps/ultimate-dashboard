<?php
/**
 * Headline color field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings     = get_option( 'udb_settings' );
	$accent_color = isset( $settings['headline_color'] ) ? $settings['headline_color'] : '#23282d';

	echo '<input type="text" name="udb_settings[headline_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-field udb-headline-color-settings-field" data-default="#23282d" />';

};
