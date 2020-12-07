<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_features() ) {
		
		// Features
		wp_enqueue_script( 'udb-features', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/js/features.js', array('jquery'), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

};
