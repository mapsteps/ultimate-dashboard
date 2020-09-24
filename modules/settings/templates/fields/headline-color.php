<?php
/**
 * Headline color field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['headline_color'] ) ) {
		$accent_color = 0;
	} else {
		$accent_color = $settings['headline_color'];
	}

	echo '<input type="text" name="udb_settings[headline_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-field udb-headline-color-settings-field" data-default="#23282d" />';

};
