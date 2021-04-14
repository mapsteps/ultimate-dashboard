<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_settings() ) {

		// Color pickers.
		wp_enqueue_script( 'wp-color-picker' );

		// CodeMirror.
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// Settings page.
		wp_enqueue_script( 'udb-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/settings.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

};
