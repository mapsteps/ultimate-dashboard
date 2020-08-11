<?php
/**
 * Menu editor module.
 *
 * @package Ultimate Dashboard PRO
 *
 * @subpackage Ultimate Dashboard PRO Menu Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Enqueue scripts & styles.
 */
function udb_admin_menu_admin_scripts() {

	global $typenow;

	// Menu editor screen.
	if ( 'udb_widgets' === $typenow && isset( $_GET['page'] ) && 'udb_admin_menu' === $_GET['page'] ) {

		// Enqueue assets here.

	}

}
add_action( 'admin_enqueue_scripts', 'udb_admin_menu_admin_scripts' );

// Submenu.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-menu/inc/submenu.php';

// Enqueue assets.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-menu/inc/enqueue.php';

// AJAX handlers.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-menu/ajax/get-menu.php';
