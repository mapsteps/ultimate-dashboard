<?php
/**
 * Export processing.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings          = array();
	$branding_settings = array();
	$login_settings    = array();

	if ( isset( $_POST['udb_export_settings'] ) ) {
		$settings          = get_option( 'udb_settings' );
		$branding_settings = get_option( 'udb_branding' );
		$login_settings    = get_option( 'udb_login' );
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
				$meta_value = is_serialized( $meta_value ) ? unserialize( $meta_value ) : $meta_value;
				// The export in the past wasn't serialized, so let's double unserialize $meta_value.
				$meta_value = is_serialized( $meta_value ) ? unserialize( $meta_value ) : $meta_value;
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
				$meta_value = is_serialized( $meta_value ) ? unserialize( $meta_value ) : $meta_value;
				// The export in the past wasn't serialized, so let's double unserialize $meta_value.
				$meta_value = is_serialized( $meta_value ) ? unserialize( $meta_value ) : $meta_value;
			}

			$admin_page->meta[ $meta_key ] = count( $meta_value ) > 1 ? $meta_value : $meta_value[0];

		}
	}

	$export_data = array(
		'widgets'           => $widgets,
		'settings'          => $settings,
		'branding_settings' => $branding_settings,
		'login_settings'    => $login_settings,
		'admin_pages'       => $admin_pages,
	);

	$export_data = apply_filters( 'udb_export', $export_data ); // What about calling this just udb_export?

	header( 'Content-disposition: attachment; filename=udb-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
	header( 'Content-type: application/json' );

	echo wp_json_encode( $export_data );
	exit;

};
