<?php
/**
 * Export processing.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Array_Helper;

return function () {

	$array_helper = new Array_Helper();

	$selected_modules = isset( $_POST['udb_export_modules'] ) && is_array( $_POST['udb_export_modules'] ) ? $_POST['udb_export_modules'] : array();

	$export_data = array();

	if ( in_array( 'modules_manager', $selected_modules, true ) ) {
		$modules_manager_settings = get_option( 'udb_modules', [] );

		if ( ! empty( $modules_manager_settings ) ) {
			$export_data['modules_manager_settings'] = $modules_manager_settings;
		}
	}

	if ( in_array( 'settings', $selected_modules, true ) ) {
		$settings = get_option( 'udb_settings', [] );

		if ( ! empty( $settings ) ) {
			$export_data['settings'] = $settings;
		}
	}

	if ( in_array( 'widgets', $selected_modules, true ) ) {
		$widgets = get_posts(
			array(
				'post_type'   => 'udb_widgets',
				'numberposts' => -1,
			)
		);
		$widgets = $widgets ? $widgets : array();

		if ( ! empty( $widgets ) ) {
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

			$export_data['widgets'] = $widgets;
		}
	}

	if ( in_array( 'branding', $selected_modules, true ) ) {
		$branding_settings = get_option( 'udb_branding', [] );

		if ( ! empty( $branding_settings ) ) {
			$export_data['branding_settings'] = $branding_settings;
		}
	}

	if ( in_array( 'login_customizer', $selected_modules, true ) ) {
		$login_customizer_settings = get_option( 'udb_login', [] );

		if ( ! empty( $login_customizer_settings ) ) {
			$export_data['login_customizer_settings'] = $login_customizer_settings;
		}
	}

	if ( in_array( 'login_redirect', $selected_modules, true ) ) {
		$login_redirect_settings = get_option( 'udb_login_redirect', [] );

		if ( ! empty( $login_redirect_settings ) ) {
			$export_data['login_redirect_settings'] = $login_redirect_settings;
		}
	}

	if ( in_array( 'admin_pages', $selected_modules, true ) ) {
		$admin_pages = get_posts(
			array(
				'post_type'   => 'udb_admin_page',
				'numberposts' => -1,
			)
		);
		$admin_pages = $admin_pages ? $admin_pages : array();

		if ( ! empty( $admin_pages ) ) {
			foreach ( $admin_pages as &$admin_page ) {
				$meta = get_post_meta( $admin_page->ID );

				$admin_page->meta = array();

				foreach ( $meta as $meta_key => $raw_meta_value ) {

					$meta_value = get_post_meta( $admin_page->ID, $meta_key, true );

					// Backwards compatibility: check for nested-serialized data.
					if ( false !== stripos( $meta_key, '_roles' ) || false !== stripos( $meta_key, '_users' ) ) {
						$meta_value = $array_helper->clean_unserialize( $meta_value, 3 );
					}

					$admin_page->meta[ $meta_key ] = $meta_value;

				}
			}

			$export_data['admin_pages'] = $admin_pages;
		}
	}

	$export_data = apply_filters( 'udb_export', $export_data );

	$time = strtotime( 'now' );
	$time = false === $time ? null : $time;

	header( 'Content-disposition: attachment; filename=udb-export-' . gmdate( 'Y-m-d-H.i.s', $time ) . '.json' );
	header( 'Content-type: application/json' );

	echo wp_json_encode( $export_data );
	exit;

};
