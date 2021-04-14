<?php
/**
 * Enable branding field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding     = get_option( 'udb_branding' );
	$version_text = isset( $branding['version_text'] ) ? $branding['version_text'] : false;

	echo '<input type="text" name="udb_branding[version_text]" class="all-options" value="' . esc_attr( $version_text ) . '" />';

};
