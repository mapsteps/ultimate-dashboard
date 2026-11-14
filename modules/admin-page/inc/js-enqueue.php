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
		wp_enqueue_script( 'icon-picker' );

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

		wp_localize_script(
			'udb-edit-admin-page',
			'udbEditAdminPage',
			array(
				'titleRequired' => __( 'Add a title to publish this admin page. It\'s the page\'s menu name.', 'ultimate-dashboard' ),
			)
		);

	} elseif ( $module->screen()->is_admin_page_list() ) {

		// Admin page list.
		wp_enqueue_script( 'udb-admin-page-list', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/post-list.js', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

};
