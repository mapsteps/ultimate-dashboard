<?php
/**
 * Init
 *
 * @package Ultimate Dashboard
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ultimate Dashboard Tools Page
 */
function udb_tools_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Tools', 'Tools', 'manage_options', 'tools', 'udb_tools_page_callback' );
}
add_action( 'admin_menu', 'udb_tools_page', 20 );

/**
 * Ultimate Dashboard Settings Page
 */
function udb_options_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Settings', 'Settings', 'manage_options', 'settings', 'udb_options_page_callback' );
}
add_action( 'admin_menu', 'udb_options_page', 20 );

/**
 * Ultimate Dashboard Addons Page
 */
function udb_addons_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Ultimate Dashboard PRO', 'PRO', 'manage_options', 'addons', 'udb_addons_page_callback' );
}
add_action( 'admin_menu', 'udb_addons_page', 10 );

/**
 * Ultimate Dashboard Tools Page Template
 */
function udb_tools_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/templates/tools-template.php';
}

/**
 * Ultimate Dashboard Settings Page Template
 */
function udb_options_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/templates/settings-template.php';
}

/**
 * Ultimate Dashboard Addon Page Template
 */
function udb_addons_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/templates/addons-template.php';
}

// Backwards Compatibility.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/backwards-compatibility.php';

// Ultimate Dashboard Custom Post Type.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/cpt.php';

// Helpers.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/helpers.php';

// Ultimate Dashboard Tools.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/tools.php';

// Ultimate Dashboard Settings.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/settings.php';

// Ultimate Dashboard Fields.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/fields.php';

/* Widgets */

// Icon Widget
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/widgets/icon-widget.php';

// Text Widget
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/widgets/text-widget.php';

// Ultimate Dashboard Output.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/output.php';
