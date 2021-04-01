<?php
/**
 * Import processing.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Array_Helper;

return function () {

	$array_helper = new Array_Helper();
	$import_file  = $_FILES['udb_import_file'];
	$file_name    = basename( sanitize_file_name( wp_unslash( $import_file['name'] ) ) );
	$explodes     = explode( '.', $file_name );
	$ext          = end( $explodes );

	// wp_check_filetype fails here, so let's check it manually.
	if ( 'json' !== $ext ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a valid .json file', 'ultimate-dashboard' )
		);

		return;

	}

	$tmp_file = $import_file['tmp_name'];

	if ( empty( $tmp_file ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a file to import', 'ultimate-dashboard' )
		);

		return;

	}

	$imports = file_get_contents( $tmp_file, true );
	$imports = (array) json_decode( $imports, true );

	// Retrieve settings & widgets.
	$feature_settings  = isset( $imports['feature_settings'] ) ? $imports['feature_settings'] : array();
	$settings          = isset( $imports['settings'] ) ? $imports['settings'] : array();
	$branding_settings = isset( $imports['branding_settings'] ) ? $imports['branding_settings'] : array();
	$login_settings    = isset( $imports['login_settings'] ) ? $imports['login_settings'] : array();
	$widgets           = isset( $imports['widgets'] ) ? $imports['widgets'] : array();
	$admin_pages       = isset( $imports['admin_pages'] ) ? $imports['admin_pages'] : array();

	if ( ! $imports ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Your import file is empty', 'ultimate-dashboard' )
		);

		return;

	}

	if ( $feature_settings || $settings || $branding_settings || $login_settings ) {

		if ( $feature_settings ) {
			update_option( 'udb_modules', $feature_settings );
		}

		if ( $settings ) {
			update_option( 'udb_settings', $settings );
		}

		if ( $branding_settings ) {
			update_option( 'udb_branding', $branding_settings );
		}

		if ( $login_settings ) {
			update_option( 'udb_login', $login_settings );
		}

		do_action( 'udb_import_settings', $imports );

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Settings imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

	if ( $widgets ) {

		foreach ( $widgets as $widget ) {

			// For backwards compatibility: before version 3, post_type was unset in the export.
			if ( ! isset( $widget['post_type'] ) ) {
				$widget['post_type'] = 'udb_widgets';
			}

			$post = get_page_by_path( $widget['post_name'], OBJECT, 'udb_widgets' );
			$meta = $widget['meta'];

			unset( $widget['meta'] );

			if ( $post ) {

				$post_id      = $post->ID;
				$widget['ID'] = $post->ID;

				wp_update_post( $widget );

			} else {

				unset( $widget['ID'] );

				$post_id = wp_insert_post( $widget );

			}

			foreach ( $meta as $meta_key => $meta_value ) {
				if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
					$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
				}

				update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Widgets imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

	if ( $admin_pages ) {

		foreach ( $admin_pages as $admin_page ) {
			$post = get_page_by_path( $admin_page['post_name'], OBJECT, 'udb_admin_page' );
			$meta = $admin_page['meta'];

			unset( $admin_page['meta'] );

			// if post exists.
			if ( $post ) {
				$post_id          = $post->ID;
				$admin_page['ID'] = $post->ID;

				wp_update_post( $admin_page );
			} else {
				unset( $admin_page['ID'] );

				$post_id = wp_insert_post( $admin_page );
			}

			foreach ( $meta as $meta_key => $meta_value ) {
				if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
					$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
				}

				update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Admin pages imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

	do_action( 'udb_import', $imports );

};
