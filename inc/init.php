<?php
/**
 * Init.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/* Output */

// Output.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . '/inc/output.php';

/* Modules */

if ( apply_filters( 'udb_login_customizer', true ) ) {
	// Login customizer.
	require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/login-customizer/login-customizer.php';
}

// Admin pages.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-page/admin-page.php';

// Admin menu.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/admin-menu.php';
