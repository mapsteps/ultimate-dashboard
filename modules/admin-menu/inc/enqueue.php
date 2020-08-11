<?php
/**
 * Setup admin menu enqueue.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Enqueue the admin menu styles & scripts.
 */
function udb_admin_menu_admin_assets() {

	global $current_screen;

	// Admin menu's setting page.
	if ( 'udb_widgets_page_udb_admin_menu' === $current_screen->id ) {

		// Styles.
		if ( apply_filters( 'udb_font_awesome', true ) ) {
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		}
		wp_enqueue_style( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/dashicons-picker.css', array(), '4.7.0' );

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_style( 'udb-admin-menu', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-menu/assets/css/admin-menu.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Scripts.
		wp_enqueue_script( 'dashicons-picker', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/dashicons-picker.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_enqueue_script( 'udb-admin-menu', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-menu/assets/js/admin-menu.js', array( 'jquery', 'dashicons-picker', 'jquery-ui-sortable' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

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

}
add_action( 'admin_enqueue_scripts', 'udb_admin_menu_admin_assets' );
