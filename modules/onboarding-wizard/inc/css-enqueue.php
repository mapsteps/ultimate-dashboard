<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_wizard() ) {

		// Select2 CSS.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Tiny slider.
		wp_enqueue_style( 'tiny-slider', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/tiny-slider.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Onboarding Wizard.
		wp_enqueue_style( 'udb-wizard', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/onboarding-wizard/assets/css/onboarding-wizard.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
