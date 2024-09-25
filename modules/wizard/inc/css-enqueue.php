<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_wizard() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		wp_enqueue_style( 'tiny-slider', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/wizard/assets/css/tiny-slider.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Wizard.
		wp_enqueue_style( 'udb-wizard', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/wizard/assets/css/wizard.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Select2 CSS.
		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
