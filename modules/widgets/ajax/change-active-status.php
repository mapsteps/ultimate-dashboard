<?php
/**
 * Change active status.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// why are we sanitizing text fields here? for is_active and post_id we even do absint & sanitize_text_field.

return function () {

	$nonce     = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	$post_id   = isset( $_POST['post_id'] ) ? absint( sanitize_text_field( $_POST['post_id'] ) ) : 0;
	$page      = get_post( $post_id );
	$is_active = isset( $_POST['is_active'] ) ? absint( sanitize_text_field( $_POST['is_active'] ) ) : 0;

	if ( ! wp_verify_nonce( $nonce, 'udb_widget_' . $post_id . '_change_active_status' ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
	}

	if ( ! $page ) {
		wp_send_json_error( __( 'Page/post not found', 'ultimate-dashboard' ) );
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
