<?php
/**
 * Temporarily set `wp_doing_ajax` to false when getting admin menu inside ajax request.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION' ) || die( "Can't access directly" );

/**
 * This function needs be called directly (without being hooked) to prevent being overlapped by other plugin.
 *
 * @see ultimate-dashboard/ultimate-dashboard.php
 *
 * Example case: Yoast SEO.
 * When inside ajax request, they do specific checking before adding their menu.
 * And that checking execution happens is called directly in the main scope (without being hooked).
 *
 * Other example: TablePress.
 * TablePress has some controllers such as: frontend, backend, & ajax.
 * They don't load frontend controller if it's inside admin area.
 * Even they don't load backend controller if it's inside admin area but is doing ajax.
 * Their admin page registration is inside that backend controller.
 * While we get the menu with role simulation through ajax.
 * See "run" function inside class-tablepress.php file.
 *
 * However, we still won't be able to get the menu reliably if:
 * - it is executed earlier than UDB, and they do similar stuff like Yoast SEO
 * - or, the check for DOING_AJAX constant directly instead checking for wp_doing_ajax() (like what Loco Translate does)
 * That's why we still need to save the recent menu later (see class-admin-menu-module.php).
 */
function udb_admin_menu_not_doing_ajax() {

	// Stop if current request is not an ajax request.
	if ( ! is_admin() || ! wp_doing_ajax() ) {
		return;
	}

	// Stop if current ajax request is not our specific ajax request.
	if ( ! isset( $_POST['action'] ) || 'udb_admin_menu_get_menu' !== $_POST['action'] ) {
		return;
	}

	/**
	 * The value of `wp_doing_ajax` will be set back to `true` in class-get-menu.php file
	 * inside `load_menu` function.
	 *
	 * Also, the checkings above are enough.
	 * We don't need to check whether admin menu module is loaded or not.
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/ajax/class-get-menu.php
	 */
	add_filter( 'wp_doing_ajax', '__return_false' );

}
