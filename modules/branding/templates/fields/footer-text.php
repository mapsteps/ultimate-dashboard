<?php
/**
 * Enable branding field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['footer_text'] ) ) {
		$footer_text = false;
	} else {
		$footer_text = $branding['footer_text'];
	}

	echo '<input type="text" name="udb_branding[footer_text]" class="all-options" value="' . esc_attr( $footer_text ) . '" />';

};
