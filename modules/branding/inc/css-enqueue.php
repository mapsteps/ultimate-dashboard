<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_branding() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Settings page.
		wp_enqueue_style( 'udb-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/settings.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
