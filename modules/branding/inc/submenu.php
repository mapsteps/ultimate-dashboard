<?php
/**
 * Setup branding submenu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Branding page.
 */
function udb_branding_page() {
	add_submenu_page( 'edit.php?post_type=udb_widgets', 'White Label', 'White Label', apply_filters( 'udb_settings_capability', 'manage_options' ), 'branding', 'udb_branding_page_callback' );
}
add_action( 'admin_menu', 'udb_branding_page' );

/**
 * Branding page callback.
 */
function udb_branding_page_callback() {
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/branding/templates/branding-template.php';
}