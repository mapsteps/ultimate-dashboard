<?php
/**
 * Enable branding field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding    = get_option( 'udb_branding' );
	$footer_text = isset( $branding['footer_text'] ) ? $branding['footer_text'] : false;

	echo '<input type="text" name="udb_branding[footer_text]" class="all-options" value="' . esc_attr( $footer_text ) . '" />';

};
