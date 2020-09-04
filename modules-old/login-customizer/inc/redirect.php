<?php
/**
 * Setup login customizer redirects.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Redirect "Login Customizer" frontend page to WordPress customizer page.
 */
function udb_redirect_login_customizer_frontend_page() {

	if ( ! isset( $_GET['page'] ) || 'udb-login-page' !== $_GET['page'] ) {
		return;
	}

	// Pull the Login Designer page from options.
	$page = get_page_by_path( 'udb-login-page' );

	// Generate the redirect url.
	$redirect_url = add_query_arg(
		array(
			'autofocus[panel]' => 'udb_login_customizer_panel',
			'url'              => rawurlencode( get_permalink( $page ) ),
		),
		admin_url( 'customize.php' )
	);

	wp_safe_redirect( $redirect_url );

}
add_action( 'admin_init', 'udb_redirect_login_customizer_frontend_page' );

/**
 * Redirect "Login Customizer" edit page to WordPress customizer page.
 */
function udb_redirect_login_customizer_edit_page() {

	global $pagenow;

	// Pull the Login Designer page from options.
	$page = get_page_by_path( 'udb-login-page' );

	if ( ! $page ) {
		return;
	}

	// Generate the redirect url.
	$redirect_url = add_query_arg(
		array(
			'autofocus[panel]' => 'udb_login_customizer_panel',
			'url'              => rawurlencode( get_permalink( $page ) ),
		),
		admin_url( 'customize.php' )
	);

	if ( 'post.php' === $pagenow && ( isset( $_GET['post'] ) && intval( $page->ID ) === intval( $_GET['post'] ) ) ) {
		wp_safe_redirect( $redirect_url );
	}

}
add_action( 'admin_init', 'udb_redirect_login_customizer_edit_page' );
