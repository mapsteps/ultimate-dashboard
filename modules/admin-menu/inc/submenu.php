<?php
/**
 * Setup menu editor submenu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Menu editor page.
 */
function udb_admin_menu() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Admin Menu', 'Admin Menu', apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_admin_menu', 'udb_admin_menu_callback' );
}
add_action( 'admin_menu', 'udb_admin_menu' );

/**
 * Menu editor page callback.
 */
function udb_admin_menu_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-menu/templates/template.php';
}
