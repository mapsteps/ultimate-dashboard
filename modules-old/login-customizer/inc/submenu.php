<?php
/**
 * Setup login customizer submenu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Add "Login Customizer" submenu under "Ultimate Dashboard" menu item.
 */
function udb_login_customizer_submenu() {
	global $submenu;

	$udb_slug = 'edit.php?post_type=udb_widgets';

	// E.g: subscriber got error if we don't return.
	if ( ! isset( $submenu[ $udb_slug ] ) ) {
		return;
	}

	array_push(
		$submenu[ $udb_slug ],
		array(
			__( 'Login Customizer', 'ultimate-dashboard' ),
			apply_filters( 'udb_settings_capability', 'manage_options' ),
			esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=udb_login_customizer_panel' ) ),
		)
	);

}
add_action( 'admin_menu', 'udb_login_customizer_submenu' );
