<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_tools() ) {

		// Settings page.
		wp_enqueue_style( 'settings-page', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/settings-page.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Settings.
		wp_enqueue_style( 'udb-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/settings.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
