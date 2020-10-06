<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_branding() ) {

		// Color pickers.
		wp_enqueue_script( 'wp-color-picker' ); // should in theory be added by the PRO version.

		// Settings page.
		wp_enqueue_script( 'udb-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/settings.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );  // should in theory be added by the PRO version.

		do_action( 'udb_branding_scripts' );

	}

};
