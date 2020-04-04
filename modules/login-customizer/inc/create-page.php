<?php
/**
 * Create login customizer page.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Register rewrite tags.
 *
 * @return void
 */
function udb_login_customizer_rewrite_tags() {
	add_rewrite_tag( '%udb-login-customizer%', '([^&]+)' );
}
add_action( 'init', 'udb_login_customizer_rewrite_tags' );

/**
 * Register rewrite rules.
 *
 * @return void
 */
function udb_login_customizer_rewrite_rules() {
	// Rewrite rule for "udb-login-customizer" page.
	add_rewrite_rule(
		'^udb-login-customizer/?',
		'index.php?pagename=udb-login-customizer',
		'top'
	);

	// Flush the rewrite rules if it hasn't been flushed.
	if ( ! get_option( 'udb_login_customizer_flush_url' ) ) {
		flush_rewrite_rules( false );
		update_option( 'udb_login_customizer_flush_url', 1 );
	}
}
add_action( 'init', 'udb_login_customizer_rewrite_rules' );

/**
 * Set page manually by modifying 404 page.
 *
 * @return void
 */
function udb_login_customizer_set_page() {
	// Only modify 404 page.
	if ( ! is_404() ) {
		return;
	}

	$pagename = sanitize_text_field( get_query_var( 'pagename' ) );

	// Only set for intended page.
	if ( 'udb-login-customizer' !== $pagename ) {
		return;
	}

	// If user is not logged-in, then redirect.
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( home_url() );
		exit;
	} else {
		// Only allow user with 'manage_options' capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_safe_redirect( home_url() );
			exit;
		}
	}

	status_header( 200 );
	load_template( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/login-customizer/templates/udb-login-page.php', true );
	exit;
}
add_action( 'wp', 'udb_login_customizer_set_page' );
