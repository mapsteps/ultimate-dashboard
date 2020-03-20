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
			__( 'Login Customizer', 'ultimatedashboard' ),
			apply_filters( 'udb_settings_capability', 'manage_options' ),
			esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=udb_login_customizer_panel' ) ),
		)
	);

	$udb_submenu   = $submenu[ $udb_slug ];
	$tools_index   = 12;
	$prolink_index = 13;
	$login_index   = 14;

	foreach ( $udb_submenu as $index => $args ) {
		if ( 'tools' === $args[2] ) {
			$tools_index = $index;
		} elseif ( 'udb-license' === $args[2] ) {
			$prolink_index = $index;
		} else {
			if ( false !== stripos( $args[2], 'udb_login_customizer', true ) ) {
				$login_index = $index;
			}
		}
	}

	// Move the 'Login Customizer' submenu up above the 'Tools' submenu.
	$submenu[ $udb_slug ][ $tools_index ]   = $udb_submenu[ $login_index ];
	$submenu[ $udb_slug ][ $prolink_index ] = $udb_submenu[ $tools_index ];
	$submenu[ $udb_slug ][ $login_index ]   = $udb_submenu[ $prolink_index ];

}
add_action( 'admin_menu', 'udb_login_customizer_submenu' );
