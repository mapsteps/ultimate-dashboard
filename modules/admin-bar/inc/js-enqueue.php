<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_bar() ) {

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Select2.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Icon picker. The old Dashicons picker stays loaded: PRO's scripts depend on it.
		wp_enqueue_script( 'icon-picker' );
		wp_enqueue_script( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/dashicons-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Template tags.
		wp_enqueue_script( 'udb-admin', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/template-tags.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Admin menu.
		wp_enqueue_script( 'udb-admin-bar', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar/assets/js/admin-bar.js', array( 'jquery', 'dashicons-picker', 'jquery-ui-sortable', 'wp-escape-html' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Visibility.
		wp_enqueue_script( 'udb-admin-bar-visibility', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar/assets/js/admin-bar-visibility.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		$admin_bar_data = array(
			'templates' => array(
				'menuList'    => require __DIR__ . '/../templates/menu-list.php',
				'submenuList' => require __DIR__ . '/../templates/submenu-list.php',
			),
		);

		$admin_bar_data = apply_filters( 'udb_admin_bar_js_object', $admin_bar_data );

		wp_localize_script(
			'udb-admin-bar',
			'udbAdminBar',
			$admin_bar_data
		);

		wp_localize_script(
			'udb-admin-bar-visibility',
			'udbAdminBarVisibility',
			array(
				'nonce'     => wp_create_nonce( 'udb_admin_bar_save_remove_by_roles' ),
				'action'    => 'udb_admin_bar_save_remove_by_roles',
				'ajaxURL'   => admin_url( 'admin-ajax.php' ),
				'errorText' => __( 'Saving failed. Please reload the page and try again.', 'ultimate-dashboard' ),
			)
		);

	}

};
