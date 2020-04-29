<?php
/**
 * Admin page.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Register post type.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/inc/post-type.php';

// Register meta boxes.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/inc/meta-boxes.php';

// Add submenu.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/inc/submenu.php';

// AJAX handlers.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/ajax/change-active-status.php';

// Enqueue assets.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/inc/enqueue.php';

// Output the process.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/inc/output.php';
