<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_menu() ) {

		// Select2.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), '4.1.0-rc.0' );

		// Dashicons picker.
		wp_enqueue_style( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/dashicons-picker.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Menu builder.
		wp_enqueue_style( 'udb-menu-builder', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-menu/assets/css/udb-menu-builder.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
