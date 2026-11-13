<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {
	if ( $module->screen()->is_new_widget() || $module->screen()->is_edit_widget() ) {

		// Select2.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), '4.1.0-rc.0' );

		// Icon picker.
		wp_enqueue_style( 'icon-picker' );

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Edit screen styles (shared with admin pages).
		wp_enqueue_style( 'udb-post-edit', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/post-edit.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Edit widget.
		wp_enqueue_style( 'udb-edit-widget', $module->url . '/assets/css/edit-widget.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		do_action( 'udb_edit_widget_styles' );

	} elseif ( $module->screen()->is_widget_list() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// List styles (shared with the admin page list).
		wp_enqueue_style( 'udb-post-list', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/post-list.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		do_action( 'udb_widget_list_styles' );

	} elseif ( $module->screen()->is_dashboard() ) {

		// Dashboard.
		wp_enqueue_style( 'udb-dashboard', $module->url . '/assets/css/dashboard.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		do_action( 'udb_dashboard_styles' );

	}
};
