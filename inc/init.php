<?php
/**
 * Init.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Settings page.
 */
function udb_options_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Settings', 'Settings', 'manage_options', 'settings', 'udb_options_page_callback' );
}
add_action( 'admin_menu', 'udb_options_page' );

/**
 * Settings page callback.
 */
function udb_options_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'templates/settings-template.php';
}

/**
 * Tools page.
 */
function udb_tools_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Tools', 'Tools', 'manage_options', 'tools', 'udb_tools_page_callback' );
}
add_action( 'admin_menu', 'udb_tools_page', 20 );

/**
 * Tools page callback.
 */
function udb_tools_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'templates/tools-template.php';
}

/**
 * PRO link.
 */
function udb_pro_link() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $submenu;

	$submenu['edit.php?post_type=udb_widgets'][] = array( 'PRO', 'manage_options', 'https://ultimatedashboard.io/pro/' );

}
add_action( 'admin_menu', 'udb_pro_link', 20 );

// Backwards compatibility.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/backwards-compatibility.php';

// Custom post type.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/cpt.php';

// Helpers.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/helpers.php';

// Tools.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/tools.php';

// Settings.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/settings.php';

// Fields.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/fields.php';

/* Widgets */

// Icon widget.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'widgets/icon-widget.php';

// Text widget.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'widgets/text-widget.php';

// HTML widget.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'widgets/html-widget.php';

/* Output */

// Output.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/output.php';

/* Modules */

// Branding.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/branding/branding.php';

if ( apply_filters( 'udb_login_customizer', true ) ) {
	// Login customizer.
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/login-customizer/login-customizer.php';
}

// Admin pages.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/admin-page.php';

// Admin menu.
// require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-menu/admin-menu.php';
