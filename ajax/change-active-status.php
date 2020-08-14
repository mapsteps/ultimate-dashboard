<?php
/**
 * Change active status
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Ajax handler of admin page's active status change.
 */
function udb_widget_change_active_status() {

	$nonce     = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
	$post_id   = isset( $_POST['post_id'] ) ? absint( sanitize_text_field( $_POST['post_id'] ) ) : 0;
	$page      = get_post( $post_id );
	$is_active = isset( $_POST['is_active'] ) ? absint( sanitize_text_field( $_POST['is_active'] ) ) : 0;

	if ( ! wp_verify_nonce( $nonce, 'udb_widget_' . $post_id . '_change_active_status' ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimatedashboard' ) );
	}

	if ( ! $page ) {
		wp_send_json_error( __( 'Post not found', 'ultimatedashboard' ) );
	}

	if ( $is_active ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'page has been activated.', 'ultimatedashboard' ),
				$page->post_title
			)
		);
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
		wp_send_json_success(
			wp_sprintf(
				'"%s" ' . __( 'page has been de-activated.', 'ultimatedashboard' ),
				$page->post_title
			)
		);
	}

}
add_action( 'wp_ajax_udb_widget_change_active_status', 'udb_widget_change_active_status' );
