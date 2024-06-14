<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_menu() ) {

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Select2.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), '4.1.0-rc.0', true );

		// Dashicons picker.
		wp_enqueue_script( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/dashicons-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Template tags.
		wp_enqueue_script( 'udb-admin', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/template-tags.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Admin menu.
		wp_enqueue_script( 'udb-admin-menu', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-menu/assets/js/admin-menu.js', array( 'jquery', 'dashicons-picker', 'jquery-ui-sortable' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		$wp_roles   = wp_roles();
		$role_names = $wp_roles->role_names;
		$roles      = array();

		foreach ( $role_names as $role_key => $role_name ) {
			array_push(
				$roles,
				array(
					'key'  => $role_key,
					'name' => $role_name,
				)
			);
		}

		$admin_menu_data = array(
			'nonces'    => array(
				'getMenu'  => wp_create_nonce( 'udb_admin_menu_get_menu' ),
				'getUsers' => wp_create_nonce( 'udb_admin_menu_get_users' ),
			),
			'roles'     => $roles,
			'templates' => array(
				'menuList'       => require __DIR__ . '/../templates/menu-list.php',
				'submenuList'    => require __DIR__ . '/../templates/submenu-list.php',
				'menuSeparator'  => require __DIR__ . '/../templates/menu-separator.php',
				'userTabMenu'    => require __DIR__ . '/../templates/user-tab-menu.php',
				'userTabContent' => require __DIR__ . '/../templates/user-tab-content.php',
			),
		);

		$admin_menu_data = apply_filters( 'udb_admin_menu_js_object', $admin_menu_data );

		wp_localize_script(
			'udb-admin-menu',
			'udbAdminMenu',
			$admin_menu_data
		);

	}

};
