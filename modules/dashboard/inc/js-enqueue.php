<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_dashboard_overview() ) {
		
		wp_enqueue_script(
			'overview-dashboard',
			ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/js/dashboard.js',
			array('jquery'), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true
		);
	}

};
