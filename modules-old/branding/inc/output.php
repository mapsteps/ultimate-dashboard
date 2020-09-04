<?php
/**
 * Output.
 *
 * @package Ultimate Dashboard PRO
 *
 * @subpackage Ultimate Dashboard PRO Branding
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Footer text.
 *
 * @param string $footer_text The footer text.
 *
 * @return string The updated footer text.
 */
function udb_branding_footer_text_output( $footer_text ) {

	$branding = get_option( 'udb_branding' );

	if ( ! empty( $branding['footer_text'] ) ) {
		$footer_text = $branding['footer_text'];
	}

	return $footer_text;

}
add_filter( 'admin_footer_text', 'udb_branding_footer_text_output' );

/**
 * Version text.
 *
 * @param string $version_text The version text.
 *
 * @return string The updated version text.
 */
function udb_branding_version_text_output( $version_text ) {

	$branding = get_option( 'udb_branding' );

	if ( ! empty( $branding['version_text'] ) ) {
		$version_text = $branding['version_text'];
	}

	return $version_text;

}
add_filter( 'update_footer', 'udb_branding_version_text_output', 20 );
