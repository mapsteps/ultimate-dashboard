<?php
/**
 * Enable branding field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['version_text'] ) ) {
		$version_text = false;
	} else {
		$version_text = $branding['version_text'];
	}

	echo '<input type="text" name="udb_branding[version_text]" class="all-options" value="' . esc_attr( $version_text ) . '" />';

};
