<?php
/**
 * Branding init.
 *
 * @package Ultimate Dashboard PRO
 *
 * @subpackage Ultimate Dashboard PRO Branding
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Enqueue scripts & styles.
 */
function udb_branding_admin_scripts() {

	global $typenow;

	// Settings screen.
	if ( 'udb_widgets' === $typenow && isset( $_GET['page'] ) && 'branding' === $_GET['page'] ) {

		// Nothing.

	}

}
add_action( 'admin_enqueue_scripts', 'udb_branding_admin_scripts' );

// Submenu.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/branding/inc/submenu.php';

// Settings.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/branding/inc/settings.php';

// Output.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/branding/inc/output.php';
