<?php
/**
 * Plugin Name: Ultimate Dashboard
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Create a custom Dashboard and give the WordPress admin area a more meaningful use.
 * Version: 3.8.12
 * Author: David Vongries
 * Author URI: https://davidvongries.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ultimate-dashboard
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Plugin constants.
define( 'ULTIMATE_DASHBOARD_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION', '3.8.12' );
define( 'ULTIMATE_DASHBOARD_PLUGIN_FILE', plugin_basename( __FILE__ ) );

/**
 * Hack to fix broken plugin updater in Ultimate Dashboard PRO 3.0.
 * This will be removed with a future update.
 */
if ( defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) && version_compare( ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, '3.0', '==' ) ) {

	/**
	 * Plugin updater function.
	 */
	function udb_pro_plugin_updater_helper() {

		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		// Retrieve our license key from the DB.
		$license_key = trim( get_option( 'ultimate_dashboard_license_key' ) );

		// Setup the updater.
		$edd_updater = new EDD_SL_Plugin_Updater(
			ULTIMATE_DASHBOARD_PRO_STORE_URL,
			ULTIMATE_DASHBOARD_PRO_PLUGIN_DIR . '\ultimate-dashboard-pro.php',
			array(
				'version' => ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION,
				'license' => $license_key,
				'item_id' => ULTIMATE_DASHBOARD_PRO_ITEM_ID,
				'author'  => 'David Vongries',
				'beta'    => false,
			)
		);

	}

	add_action( 'init', 'udb_pro_plugin_updater_helper' );

}

// Admin menu specific support.
require_once __DIR__ . '/modules/admin-menu/inc/not-doing-ajax.php';
udb_admin_menu_not_doing_ajax();

// Helper classes.
require __DIR__ . '/helpers/class-screen-helper.php';
require __DIR__ . '/helpers/class-color-helper.php';
require __DIR__ . '/helpers/class-widget-helper.php';
require __DIR__ . '/helpers/class-content-helper.php';
require __DIR__ . '/helpers/class-user-helper.php';
require __DIR__ . '/helpers/class-array-helper.php';
require __DIR__ . '/helpers/class-admin-bar-helper.php';

// Base module.
require __DIR__ . '/modules/base/class-base-module.php';
require __DIR__ . '/modules/base/class-base-output.php';

// Core classes.
require __DIR__ . '/class-backwards-compatibility.php';
require __DIR__ . '/class-vars.php';
require __DIR__ . '/class-setup.php';

/**
 * Check whether Ultimate Dashboard Pro is active.
 * This function can be called anywhere after "plugins_loaded" hook.
 *
 * @return bool
 */
function udb_is_pro_active() {
	return defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' );
}

Udb\Backwards_Compatibility::init();
Udb\Setup::init();
