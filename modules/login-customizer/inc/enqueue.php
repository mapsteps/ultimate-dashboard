<?php
/**
 * Setup login customizer enqueue.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Login customizer's localized JS object.
 *
 * @return array The login customizer's localized JS object.
 */
function udb_login_customizer_js_object() {
	return array(
		'homeUrl'      => home_url(),
		'loginPageUrl' => home_url( 'udb-login-customizer' ),
		'pluginUrl'    => rtrim( ULTIMATE_DASHBOARD_PLUGIN_URL, '/' ),
		'moduleUrl'    => ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer',
		'assetUrl'     => ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets',
		'wpLogoUrl'    => admin_url( 'images/wordpress-logo.svg?ver=' . ULTIMATE_DASHBOARD_PLUGIN_VERSION ),
	);
}

/**
 * Enqueue the login customizer control styles.
 */
function udb_login_customizer_control_styles() {

	wp_enqueue_style( 'udb-login-customizer', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/css/controls.css', null, ULTIMATE_DASHBOARD_PLUGIN_VERSION );

}
add_action( 'customize_controls_print_styles', 'udb_login_customizer_control_styles', 99 );

/**
 * Enqueue login customizer control scripts.
 */
function udb_login_customizer_control_scripts() {

	wp_enqueue_script( 'udb-login-customizer-control', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/js/controls.js', array( 'customize-controls' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	wp_enqueue_script( 'udb-login-customizer-events', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/js/preview.js', array( 'customize-controls' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	wp_localize_script(
		'customize-controls',
		'udbLoginCustomizer',
		udb_login_customizer_js_object()
	);

}
add_action( 'customize_controls_enqueue_scripts', 'udb_login_customizer_control_scripts' );

/**
 * Enqueue styles to login customizer preview styles.
 */
function udb_login_customizer_preview_styles() {

	if ( ! is_customize_preview() ) {
		return;
	}

	wp_enqueue_style( 'udb-login-customizer-hint', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/css/hint.css', ULTIMATE_DASHBOARD_PLUGIN_VERSION, 'all' );

	wp_enqueue_style( 'udb-login-customizer-preview', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/css/preview.css', ULTIMATE_DASHBOARD_PLUGIN_VERSION, 'all' );

}
add_action( 'login_enqueue_scripts', 'udb_login_customizer_preview_styles', 99 );

/**
 * Enqueue scripts to login customizer preview scripts.
 */
function udb_login_customizer_preview_scripts() {
	wp_enqueue_script( 'udb-login-customizer-preview', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/js/preview.js', array( 'customize-preview' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );\

	wp_enqueue_script( 'udb-login-customizer-hints', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/login-customizer/assets/js/hints.js', array( 'customize-preview' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	wp_localize_script(
		'customize-preview',
		'udbLoginCustomizer',
		udb_login_customizer_js_object()
	);
}
add_action( 'customize_preview_init', 'udb_login_customizer_preview_scripts' );
