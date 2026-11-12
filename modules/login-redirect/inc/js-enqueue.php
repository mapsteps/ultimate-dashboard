<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_login_redirect() ) {

		// jQuery: PRO's script on this page loads after this one and uses it.
		wp_enqueue_script( 'udb-login-redirect', $module->url . '/assets/js/login-redirect.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		$inline_script = '
			var udbLoginRedirect = {};
		';

		wp_add_inline_script( 'udb-login-redirect', $inline_script, 'before' );

	}

};
