<?php
/**
 * Save menu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Save menu.
 */
function udb_admin_menu_save_menu() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'udb_admin_menu_save_menu' ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimatedashboard' ) );
	}

	$_POST['menu'] = json_decode( stripslashes( $_POST['menu'] ), true );

	update_option( 'udb_admin_menu', $_POST['menu'] );

	wp_send_json_success( __( 'Menu updated successfully', 'ultimatedashboard' ) );
}

add_action( 'wp_ajax_udb_admin_menu_save_menu', 'udb_admin_menu_save_menu' );
