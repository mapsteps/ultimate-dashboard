<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_new_admin_page() || $module->screen()->is_edit_admin_page() ) {

		// Select2.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), '4.1.0-rc.0', true );

		// Icon picker.
		wp_enqueue_script( 'icon-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/icon-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// CodeMirror.
		wp_enqueue_code_editor(
			array(
				'type'       => 'text/html',
				'codemirror' => array(
					'autoRefresh' => true,
				),
			)
		);

		// Edit admin page.
		wp_enqueue_script( 'udb-edit-admin-page', $module->url . '/assets/js/edit-admin-page.js', array( 'select2', 'wp-escape-html' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	} elseif ( $module->screen()->is_admin_page_list() ) {

		// Admin page list.
		wp_enqueue_script( 'udb-admin-page-list', $module->url . '/assets/js/admin-page-list.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

};
