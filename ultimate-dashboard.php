<?php
/**
 * Plugin Name: Ultimate Dashboard
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Create a Custom WordPress Dashboard.
 * Version: 2.8.3
 * Author: David Vongries
 * Author URI: https://mapsteps.com/
 * Text Domain: ultimate-dashboard
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Plugin constants.
define( 'ULTIMATE_DASHBOARD_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION', '2.8.3' );

// Helper classes.
require __DIR__ . '/helpers/class-screen-helper.php';
require __DIR__ . '/helpers/class-widget-helper.php';
require __DIR__ . '/helpers/class-content-helper.php';
require __DIR__ . '/helpers/class-user-helper.php';
require __DIR__ . '/helpers/class-array-helper.php';

// Base module.
require __DIR__ . '/modules/base/class-module.php';
require __DIR__ . '/modules/base/class-output.php';

require __DIR__ . '/class-backwards-compatibility.php';
require __DIR__ . '/class-setup.php';

Udb\Backwards_Compatibility::run();
Udb\Setup::run();
