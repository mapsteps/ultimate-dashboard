<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_features() ) {

		// Features.
		wp_enqueue_script( 'udb-features', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/js/feature.js', array( 'jquery', 'wp-escape-html' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

};
