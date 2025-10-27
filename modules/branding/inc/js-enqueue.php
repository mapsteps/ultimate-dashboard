<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_branding() ) {

		// Color pickers.
		wp_enqueue_script( 'wp-color-picker' );

		// Instant preview.
		wp_enqueue_script( 'udb-branding-instant-preview', $module->url . '/assets/js/instant-preview.js', array( 'wp-polyfill', 'jquery', 'wp-escape-html' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_add_inline_script( 'udb-branding-instant-preview', 'var udbBrandingInstantPreview = {isProActive: ' . ( udb_is_pro_active() ? 'true' : 'false' ) . '};', 'before' );

	}

};
