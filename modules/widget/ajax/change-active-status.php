<?php
/**
 * Change active status.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( empty( $_POST ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'udb_widget_' . $post_id . '_change_active_status' ) ) {
		wp_send_json_error( __( 'Invalid nonce', 'ultimate-dashboard' ) );
	}

	$page      = get_post( $post_id );
	$is_active = isset( $_POST['is_active'] ) ? absint( $_POST['is_active'] ) : 0;

	if ( ! $page ) {
		wp_send_json_error( __( 'Page/post not found', 'ultimate-dashboard' ) );
	}

	$capability = apply_filters( 'udb_settings_capability', 'manage_options' );

	if ( ! current_user_can( $capability ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ) );
	}

	if ( $is_active ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'Page/post has been activated.', 'ultimate-dashboard' ),
				$page->post_title
			)
		);
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'Page/post has been de-activated.', 'ultimate-dashboard' ),
				$page->post_title
			)
		);
	}

};
