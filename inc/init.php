<?php
/**
 * Init.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Tools page.
 */
function udb_tools_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Tools', 'Tools', 'manage_options', 'tools', 'udb_tools_page_callback' );
}
add_action( 'admin_menu', 'udb_tools_page', 20 );

/**
 * Settings page.
 */
function udb_options_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Settings', 'Settings', 'manage_options', 'settings', 'udb_options_page_callback' );
}
add_action( 'admin_menu', 'udb_options_page', 20 );

/**
 * Tools page callback.
 */
function udb_tools_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/templates/tools-template.php';
}

/**
 * Settings page callback.
 */
function udb_options_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/templates/settings-template.php';
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
add_action( 'admin_menu', 'udb_pro_link' );

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
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/widgets/icon-widget.php';

// Text widget.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/widgets/text-widget.php';

/* Output */

// Output.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/output.php';
