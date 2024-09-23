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

		// Dashicons picker.
		wp_enqueue_script( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/dashicons-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Template tags.
		wp_enqueue_script( 'udb-admin', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/template-tags.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Admin menu.
		wp_enqueue_script( 'udb-admin-bar', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar/assets/js/admin-bar.js', array( 'jquery', 'dashicons-picker', 'jquery-ui-sortable' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Visibility.
		wp_enqueue_script( 'udb-admin-bar-visibility', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar/assets/js/admin-bar-visibility.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		/**
		 * These codes are not being used currently.
		 * But leave it here because in the future, if requested, it would be used for
		 * "hide menu item for specific role(s) / user(s)" functionality (inside dropdowns).
		 */
		// $wp_roles   = wp_roles();
		// $role_names = $wp_roles->role_names;
		// $roles      = array();

		// foreach ( $role_names as $role_key => $role_name ) {
		// array_push(
		// $roles,
		// array(
		// 'id'   => $role_key,
		// 'text' => $role_name,
		// )
		// );
		// }

		$admin_bar_data = array(
			/**
			 * These codes are not being used currently.
			 * But leave it here because in the future, if requested, it would be used for
			 * "hide menu item for specific role(s) / user(s)" functionality (inside dropdowns).
			 */
			// 'nonces'    => array(
			// 'getUsers' => wp_create_nonce( 'udb_admin_bar_get_users' ),
			// ),
			// 'roles'     => $roles,
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
				'nonce'   => wp_create_nonce( 'udb_admin_bar_save_remove_by_roles' ),
				'action'  => 'udb_admin_bar_save_remove_by_roles',
				'ajaxURL' => admin_url( 'admin-ajax.php' ),
			)
		);

	}

};
