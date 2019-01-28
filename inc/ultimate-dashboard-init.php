<?php
/**
 * Init
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
 * Ultimate Dashboard Settings Page Template
 */
function udb_options_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-settings-template.php';
}

/**
 * Ultimate Dashboard Addon Page Template
 */
function udb_addons_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-addons-template.php';
}

// Backwards Compatibility
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-backwards-compatibility.php';

// Ultimate Dashboard Custom Post Type
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-cpt.php';

// Helpers
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-helpers.php';

// Ultimate Dashboard Settings
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-settings.php';

// Ultimate Dashboard Fields
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-fields.php';

// Ultimate Dashboard Widget Types
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/widgets/ultimate-dashboard-icon-widget.php';

// Ultimate Dashboard Output
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-output.php';