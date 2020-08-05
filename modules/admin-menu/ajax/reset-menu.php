<?php
/**
 * Reset menu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Reset menu.
 */
function udb_admin_menu_reset_menu() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	$role  = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'udb_admin_menu_reset_menu' ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimatedashboard' ) );
	}

	if ( ! $role ) {
		wp_send_json_error( __( 'Role is not specified', 'ultimatedashboard' ) );
	}

	if ( 'all' === $role ) {
		delete_option( 'udb_admin_menu' );
	} else {
		$menu = get_option( 'udb_admin_menu', array() );

		if ( isset( $menu[ $role ] ) ) {
			unset( $menu[ $role ] );
			update_option( 'udb_admin_menu', $menu );
		}
	}

	wp_send_json_success( esc_attr( $role ) . ' ' . __( 'menu has been reset successfully', 'ultimatedashboard' ) );
}

add_action( 'wp_ajax_udb_admin_menu_reset_menu', 'udb_admin_menu_reset_menu' );
