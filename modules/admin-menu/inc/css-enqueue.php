<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_menu() ) {

		if ( apply_filters( 'udb_font_awesome', true ) ) {
			// Font Awesome.
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/font-awesome.min.css', array(), '5.14.0' );
			wp_enqueue_style( 'font-awesome-shims', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/v4-shims.min.css', array(), '5.14.0' );
		}

		// Color pickers.
		wp_enqueue_style( 'wp-color-picker' );

		// Settings page.
		wp_enqueue_style( 'settings-page', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/settings-page.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Setting fields.
		wp_enqueue_style( 'settings-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/setting-fields.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Dashicons picker.
		wp_enqueue_style( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/dashicons-picker.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Admin menu.
		wp_enqueue_style( 'udb-admin-menu', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-menu/assets/css/admin-menu.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
