<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {
	if ( $module->screen()->is_new_widget() || $module->screen()->is_edit_widget() ) {

		// Select2.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), '4.1.0-rc.0', true );

		// Icon picker.
		wp_enqueue_script( 'icon-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/icon-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Template tags.
		wp_enqueue_script( 'udb-admin', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/template-tags.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// CodeMirror.
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// Edit widget.
		wp_enqueue_script( 'udb-edit-widget', $module->url . '/assets/js/edit-widget.js', array( 'jquery', 'wp-escape-html' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		do_action( 'udb_edit_widget_scripts' );

	} elseif ( $module->screen()->is_widget_list() ) {

		// Widget list.
		wp_enqueue_script( 'udb-widget-list', $module->url . '/assets/js/widget-list.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		do_action( 'udb_widget_list_scripts' );

	} elseif ( $module->screen()->is_dashboard() ) {

		// Dashboard.
		wp_enqueue_script( 'udb-dashboard', $module->url . '/assets/js/dashboard.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		do_action( 'udb_dashboard_scripts' );

	}
};
