<?php
/**
 * Change active status
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$nonce     = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$page      = get_post( $post_id );
	$is_active = isset( $_POST['is_active'] ) ? 1 : 0;

	if ( ! wp_verify_nonce( $nonce, 'udb_admin_page_' . $post_id . '_change_active_status' ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
	}

	if ( ! $page ) {
		wp_send_json_error( __( 'Post not found', 'ultimate-dashboard' ) );
	}

	$capability = apply_filters( 'udb_settings_capability', 'manage_options' );

	if ( ! current_user_can( $capability ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ) );
	}

	if ( $is_active ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'page has been activated.', 'ultimate-dashboard' ),
				$page->post_title
			)
		);
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'page has been de-activated.', 'ultimate-dashboard' ),
				$page->post_title
			)
		);
	}

};
