<?php
/**
 * Export processing.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Array_Helper;

return function () {

	$array_helper            = new Array_Helper();
	$feature_settings        = array();
	$settings                = array();
	$branding_settings       = array();
	$login_settings          = array();
	$login_redirect_settings = array();

	if ( isset( $_POST['udb_export_settings'] ) ) {
		$feature_settings        = get_option( 'udb_modules' );
		$settings                = get_option( 'udb_settings' );
		$branding_settings       = get_option( 'udb_branding' );
		$login_settings          = get_option( 'udb_login' );
		$login_redirect_settings = get_option( 'udb_login_redirect' );
	}

	$widgets = get_posts(
		array(
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
		)
	);
	$widgets = $widgets ? $widgets : array();

	foreach ( $widgets as &$widget ) {
		$meta = get_post_meta( $widget->ID );

		$widget->meta = array();

		foreach ( $meta as $meta_key => $meta_value ) {

			// Check for serialized data (when $meta_value is array).
			if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
				$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
			}

			$widget->meta[ $meta_key ] = count( $meta_value ) > 1 ? $meta_value : $meta_value[0];

		}
	}

	$admin_pages = get_posts(
		array(
			'post_type'   => 'udb_admin_page',
			'numberposts' => -1,
		)
	);
	$admin_pages = $admin_pages ? $admin_pages : array();

	foreach ( $admin_pages as &$admin_page ) {
		$meta = get_post_meta( $admin_page->ID );

		$admin_page->meta = array();

		foreach ( $meta as $meta_key => $meta_value ) {

			// Check for serialized data (when $meta_value is array).
			if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
				$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
			}

			$admin_page->meta[ $meta_key ] = count( $meta_value ) > 1 ? $meta_value : $meta_value[0];

		}
	}

	$export_data = array(
		'feature_settings'        => $feature_settings,
		'widgets'                 => $widgets,
		'settings'                => $settings,
		'branding_settings'       => $branding_settings,
		'login_settings'          => $login_settings,
		'login_redirect_settings' => $login_redirect_settings,
		'admin_pages'             => $admin_pages,
	);

	$export_data = apply_filters( 'udb_export', $export_data ); // What about calling this just udb_export?

	header( 'Content-disposition: attachment; filename=udb-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
	header( 'Content-type: application/json' );

	echo wp_json_encode( $export_data );
	exit;

};
