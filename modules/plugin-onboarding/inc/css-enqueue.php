<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_plugin_onboarding() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Tiny slider.
		wp_enqueue_style( 'tiny-slider', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/tiny-slider.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Plugin onboarding.
		wp_enqueue_style( 'udb-plugin-onboarding', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/plugin-onboarding/assets/css/plugin-onboarding.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
