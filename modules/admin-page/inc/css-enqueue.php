<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_new_admin_page() || $module->screen()->is_edit_admin_page() ) {

		// Select2.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), '4.1.0-rc.0' );

		// Icon picker.
		wp_enqueue_style( 'icon-picker' );

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Edit screen styles (shared with widgets).
		wp_enqueue_style( 'udb-post-edit', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/post-edit.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Edit admin page.
		wp_enqueue_style( 'udb-edit-admin-page', $module->url . '/assets/css/edit-admin-page.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	} elseif ( $module->screen()->is_admin_page_list() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// List styles (shared with the widget list).
		wp_enqueue_style( 'udb-post-list', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/post-list.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
