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

	/**
	 * Whether a value is a list (keys 0, 1, 2 and so on).
	 *
	 * @param mixed $value The value.
	 *
	 * @return bool
	 */
	$is_list = function ( $value ) {

		return is_array( $value ) && array_keys( $value ) === range( 0, count( $value ) - 1 );

	};

	/**
	 * Whether an imported option has the shape of a saved setting: a non-empty array with named keys.
	 *
	 * @param mixed $value The imported value.
	 *
	 * @return bool
	 */
	$is_valid_option = function ( $value ) use ( $is_list ) {

		return is_array( $value ) && ! empty( $value ) && ! $is_list( $value );

	};

	/**
	 * Whether imported widgets / admin pages have the shape of an export: a list of posts,
	 * each with a post_name (and, if present, meta with named keys).
	 *
	 * @param mixed $value The imported value.
	 *
	 * @return bool
	 */
	$is_valid_post_list = function ( $value ) use ( $is_list ) {

		if ( empty( $value ) || ! $is_list( $value ) ) {
			return false;
		}

		foreach ( $value as $item ) {
			if ( ! is_array( $item ) || empty( $item['post_name'] ) || ! is_string( $item['post_name'] ) ) {
				return false;
			}

			if ( isset( $item['meta'] ) && ( ! is_array( $item['meta'] ) || ( ! empty( $item['meta'] ) && $is_list( $item['meta'] ) ) ) ) {
				return false;
			}
		}

		return true;

	};

	/**
	 * Create or update the imported posts (matched by post_name) and their meta.
	 *
	 * WordPress removes one level of backslashes from what wp_insert_post(), wp_update_post()
	 * and update_post_meta() get, so the values are slashed first to arrive exactly as exported.
	 *
	 * @param array  $posts     The imported posts (already validated).
	 * @param string $post_type The post type.
	 *
	 * @return int How many posts were imported.
	 */
	$import_posts = function ( $posts, $post_type ) use ( $prepare_post, $array_helper ) {

		$imported = 0;

		foreach ( $posts as $data ) {

			$post = get_page_by_path( $data['post_name'], OBJECT, $post_type );
			$meta = isset( $data['meta'] ) && is_array( $data['meta'] ) ? $data['meta'] : array();
			$data = $prepare_post( $data, $post_type );

			if ( $post ) {
				$data['ID'] = $post->ID;
				$post_id    = wp_update_post( wp_slash( $data ), true );
			} else {
				$post_id = wp_insert_post( wp_slash( $data ), true );
			}

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				continue;
			}

			foreach ( $meta as $meta_key => $meta_value ) {
				if ( ! is_string( $meta_key ) || '' === $meta_key ) {
					continue;
				}

				if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
					$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
				}

				update_post_meta( $post_id, $meta_key, wp_slash( $meta_value ) );
			}

			++$imported;

		}

		return $imported;

	};

	/**
	 * Tell the user that a part of the file was skipped because its data is damaged.
	 *
	 * @param string $label The part's name.
	 */
	$add_damaged_error = function ( $label ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			/* translators: %s: Part of the import file, e.g. "White Label settings". */
			sprintf( __( '%s not imported: this part of the file is damaged. Nothing was changed there.', 'ultimate-dashboard' ), $label )
		);

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

	if ( ! $imports ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Your import file is empty', 'ultimate-dashboard' )
		);

		return;

	}

	// Every part an export can hold, PRO's included. A file with none of them is something else.
	$known_parts = array(
		'modules_manager_settings',
		'feature_settings',
		'settings',
		'branding_settings',
		'login_customizer_settings',
		'login_settings',
		'login_redirect_settings',
		'widgets',
		'admin_pages',
		'admin_menu',
		'admin_bar',
		'multisite_settings',
	);

	if ( ! array_intersect_key( $imports, array_flip( $known_parts ) ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'This file is not a valid Ultimate Dashboard export', 'ultimate-dashboard' )
		);

		return;

	}

	// Retrieve settings & widgets.
	$modules_manager_settings  = isset( $imports['modules_manager_settings'] ) ? $imports['modules_manager_settings'] : array();
	$login_customizer_settings = isset( $imports['login_customizer_settings'] ) ? $imports['login_customizer_settings'] : array();
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

	// The settings parts: value from the file, option it goes to, name for the notices.
	$setting_parts = array(
		array( $modules_manager_settings, 'udb_modules', __( 'Modules', 'ultimate-dashboard' ) ),
		array( isset( $imports['settings'] ) ? $imports['settings'] : array(), 'udb_settings', __( 'Settings', 'ultimate-dashboard' ) ),
		array( isset( $imports['branding_settings'] ) ? $imports['branding_settings'] : array(), 'udb_branding', __( 'White Label settings', 'ultimate-dashboard' ) ),
		array( $login_customizer_settings, 'udb_login', __( 'Login Customizer settings', 'ultimate-dashboard' ) ),
		array( isset( $imports['login_redirect_settings'] ) ? $imports['login_redirect_settings'] : array(), 'udb_login_redirect', __( 'Login Redirect settings', 'ultimate-dashboard' ) ),
	);

	$settings_imported = false;

	foreach ( $setting_parts as $setting_part ) {

		list( $value, $option_name, $label ) = $setting_part;

		// Nothing in the file for this part.
		if ( empty( $value ) ) {
			continue;
		}

		if ( ! $is_valid_option( $value ) ) {
			$add_damaged_error( $label );
			continue;
		}

		// update_option() stores the value as is (no unslashing), so it's not slashed here.
		update_option( $option_name, $value );

		$settings_imported = true;

	}

	/*
	 * PRO imports its network settings on "udb_import_settings", so it also runs for a file
	 * that only holds those (an export of PRO's network settings alone).
	 */
	if ( $settings_imported || ! empty( $imports['multisite_settings'] ) ) {

		do_action( 'udb_import_settings', $imports );

		if ( $settings_imported ) {
			add_settings_error(
				'udb_export',
				esc_attr( 'udb-import' ),
				__( 'Settings imported', 'ultimate-dashboard' ),
				'updated'
			);
		}
	}

	if ( ! empty( $widgets ) ) {

		if ( ! $is_valid_post_list( $widgets ) ) {
			$add_damaged_error( __( 'Widgets', 'ultimate-dashboard' ) );
		} elseif ( $import_posts( $widgets, 'udb_widgets' ) ) {
			add_settings_error(
				'udb_export',
				esc_attr( 'udb-import' ),
				__( 'Widgets imported', 'ultimate-dashboard' ),
				'updated'
			);
		}
	}

	if ( ! empty( $admin_pages ) ) {

		if ( ! $is_valid_post_list( $admin_pages ) ) {
			$add_damaged_error( __( 'Admin pages', 'ultimate-dashboard' ) );
		} elseif ( $import_posts( $admin_pages, 'udb_admin_page' ) ) {
			add_settings_error(
				'udb_export',
				esc_attr( 'udb-import' ),
				__( 'Admin pages imported', 'ultimate-dashboard' ),
				'updated'
			);
		}
	}

	do_action( 'udb_import', $imports );

};
