<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_menu() ) {

		// Color pickers.
		wp_enqueue_script( 'wp-color-picker' );

		// CodeMirror.
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Dashicons picker.
		wp_enqueue_script( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/dashicons-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

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

		wp_localize_script(
			'udb-admin-menu',
			'udbAdminMenu',
			array(
				'nonces'    => array(
					'getMenu' => wp_create_nonce( 'udb_admin_menu_get_menu' ),
				),
				'roles'     => $roles,
				'templates' => array(
					'menuList'      => require __DIR__ . '/../templates/menu-list.php',
					'submenuList'   => require __DIR__ . '/../templates/submenu-list.php',
					'menuSeparator' => require __DIR__ . '/../templates/menu-separator.php',
				),
			)
		);

	}

};
