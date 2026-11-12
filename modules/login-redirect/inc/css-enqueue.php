<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_login_redirect() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Login redirect page.
		wp_enqueue_style( 'udb-login-redirect', $module->url . '/assets/css/login-redirect.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
