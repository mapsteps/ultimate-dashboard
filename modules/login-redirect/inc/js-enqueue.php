<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_login_redirect() ) {

		// Select2.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), '4.1.0-rc.0', true );

		wp_enqueue_script( 'udb-login-redirect', $module->url . '/assets/js/login-redirect.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		$inline_script = '
			var udbLoginRedirect = {};
		';

		wp_add_inline_script( 'udb-login-redirect', $inline_script, 'before' );

	}

};
