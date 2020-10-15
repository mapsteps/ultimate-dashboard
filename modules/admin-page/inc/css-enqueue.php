<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_new_admin_page() || $module->screen()->is_edit_admin_page() ) {

		if ( apply_filters( 'udb_font_awesome', true ) ) {
			// Font Awesome.
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/font-awesome.min.css', array(), '5.14.0' );
			wp_enqueue_style( 'font-awesome-shims', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/v4-shims.min.css', array(), '5.14.0' );
		}

		// Select2.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), '4.1.0-beta.1' );

		// Icon picker.
		wp_enqueue_style( 'icon-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/icon-picker.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Edit screen.
		wp_enqueue_style( 'udb-edit-screen', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/edit-screen.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Admin fields.
		wp_enqueue_style( 'udb-admin-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/admin-fields.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	} elseif ( $module->screen()->is_admin_page_list() ) {

		if ( apply_filters( 'udb_font_awesome', true ) ) {
			// Font Awesome.
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/font-awesome.min.css', array(), '5.14.0' );
			wp_enqueue_style( 'font-awesome-shims', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/v4-shims.min.css', array(), '5.14.0' );
		}

		// Admin page list.
		wp_enqueue_style( 'udb-admin-page-list', $module->url . '/assets/css/admin-page-list.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Admin fields.
		wp_enqueue_style( 'udb-admin-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/admin-fields.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
