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

	// Runs in Tool_Module::process_import(), which already checked the nonce and capability.
	// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- See above; only name, error and tmp_name are used, checked below.
	$import_file = isset( $_FILES['udb_import_file'] ) && is_array( $_FILES['udb_import_file'] ) ? $_FILES['udb_import_file'] : null;

	/**
	 * Build the post data for wp_insert_post() / wp_update_post() from an imported post.
	 * Only content fields are taken from the file; the post type is always ours.
	 *
	 * @param array  $data      The imported post.
	 * @param string $post_type The post type.
	 *
	 * @return array
	 */
	$prepare_post = function ( $data, $post_type ) {

		$post = array( 'post_type' => $post_type );

		foreach ( array( 'post_title', 'post_name', 'post_content', 'post_excerpt', 'menu_order' ) as $field ) {
			if ( isset( $data[ $field ] ) && is_scalar( $data[ $field ] ) ) {
				$post[ $field ] = $data[ $field ];
			}
		}

		$status              = isset( $data['post_status'] ) ? $data['post_status'] : 'publish';
		$post['post_status'] = in_array( $status, array( 'publish', 'draft', 'pending', 'private' ), true ) ? $status : 'publish';

		return $post;

	};

	if ( is_null( $import_file ) || empty( $import_file['name'] ) || ( isset( $import_file['error'] ) && UPLOAD_ERR_NO_FILE === (int) $import_file['error'] ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please select a file to import', 'ultimate-dashboard' )
		);

		return;

	}

	$file_name = basename( sanitize_file_name( wp_unslash( $import_file['name'] ) ) );
	$explodes  = explode( '.', $file_name );
	$ext       = end( $explodes );

	// wp_check_filetype fails here, so let's check it manually.
	if ( 'json' !== $ext ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a valid .json file', 'ultimate-dashboard' )
		);

		return;

	}

	$tmp_file = isset( $import_file['tmp_name'] ) ? $import_file['tmp_name'] : '';

	if ( empty( $tmp_file ) || ( isset( $import_file['error'] ) && UPLOAD_ERR_OK !== (int) $import_file['error'] ) || ! is_uploaded_file( $tmp_file ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a file to import', 'ultimate-dashboard' )
		);

		return;

	}

	$imports = json_decode( (string) file_get_contents( $tmp_file ), true );

	if ( ! is_array( $imports ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'This file is not a valid Ultimate Dashboard export', 'ultimate-dashboard' )
		);

		return;

	}

	// Retrieve settings & widgets.
	$modules_manager_settings  = isset( $imports['modules_manager_settings'] ) ? $imports['modules_manager_settings'] : array();
	$settings                  = isset( $imports['settings'] ) ? $imports['settings'] : array();
	$branding_settings         = isset( $imports['branding_settings'] ) ? $imports['branding_settings'] : array();
	$login_customizer_settings = isset( $imports['login_customizer_settings'] ) ? $imports['login_customizer_settings'] : array();
	$login_redirect_settings   = isset( $imports['login_redirect_settings'] ) ? $imports['login_redirect_settings'] : array();
	$widgets                   = isset( $imports['widgets'] ) ? $imports['widgets'] : array();
	$admin_pages               = isset( $imports['admin_pages'] ) ? $imports['admin_pages'] : array();

	// Backwards compatibility for "feature_settings".
	if ( empty( $modules_manager_settings ) ) {
		$modules_manager_settings = isset( $imports['feature_settings'] ) ? $imports['feature_settings'] : $modules_manager_settings;
	}

	// Backwards compatibility for "login_settings".
	if ( empty( $login_customizer_settings ) ) {
		$login_customizer_settings = isset( $imports['login_settings'] ) ? $imports['login_settings'] : $login_customizer_settings;
	}

	if ( ! $imports ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Your import file is empty', 'ultimate-dashboard' )
		);

		return;

	}

	$has_free_settings = $modules_manager_settings || $settings || $branding_settings || $login_customizer_settings || $login_redirect_settings;

	/*
	 * PRO imports its network settings on "udb_import_settings", so it also runs for a file
	 * that only holds those (an export of PRO's network settings alone).
	 */
	if ( $has_free_settings || ! empty( $imports['multisite_settings'] ) ) {

		if ( $modules_manager_settings ) {
			update_option( 'udb_modules', $modules_manager_settings );
		}

		if ( $settings ) {
			update_option( 'udb_settings', $settings );
		}

		if ( $branding_settings ) {
			update_option( 'udb_branding', $branding_settings );
		}

		if ( $login_customizer_settings ) {
			update_option( 'udb_login', $login_customizer_settings );
		}

		if ( $login_redirect_settings ) {
			update_option( 'udb_login_redirect', $login_redirect_settings );
		}

		do_action( 'udb_import_settings', $imports );

		if ( $has_free_settings ) {
			add_settings_error(
				'udb_export',
				esc_attr( 'udb-import' ),
				__( 'Settings imported', 'ultimate-dashboard' ),
				'updated'
			);
		}
	}

	if ( $widgets ) {

		foreach ( $widgets as $widget ) {

			if ( ! is_array( $widget ) || empty( $widget['post_name'] ) ) {
				continue;
			}

			$post   = get_page_by_path( $widget['post_name'], OBJECT, 'udb_widgets' );
			$meta   = isset( $widget['meta'] ) && is_array( $widget['meta'] ) ? $widget['meta'] : array();
			$widget = $prepare_post( $widget, 'udb_widgets' );

			if ( $post ) {

				$post_id      = $post->ID;
				$widget['ID'] = $post->ID;

				wp_update_post( $widget );

			} else {

				$post_id = wp_insert_post( $widget );

			}

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				continue;
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
			if ( ! is_array( $admin_page ) || empty( $admin_page['post_name'] ) ) {
				continue;
			}

			$post       = get_page_by_path( $admin_page['post_name'], OBJECT, 'udb_admin_page' );
			$meta       = isset( $admin_page['meta'] ) && is_array( $admin_page['meta'] ) ? $admin_page['meta'] : array();
			$admin_page = $prepare_post( $admin_page, 'udb_admin_page' );

			// if post exists.
			if ( $post ) {
				$post_id          = $post->ID;
				$admin_page['ID'] = $post->ID;

				wp_update_post( $admin_page );
			} else {
				$post_id = wp_insert_post( $admin_page );
			}

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				continue;
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
