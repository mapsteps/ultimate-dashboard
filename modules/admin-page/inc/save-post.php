<?php
/**
 * Admin page saving process.
 *
 * @package Ultimate_Dashboard
 */

// are we sanitizing everything correctly? Not sure if all of those are actually text fields.

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module, $post_id ) {

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'udb_admin_page' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['udb_nonce'] ) || ! wp_verify_nonce( $_POST['udb_nonce'], 'udb_edit_admin_page' )) {
		return;
	}

	// Active status.
	if ( isset( $_POST['udb_is_active'] ) ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
	}

	// Content type.
	if ( isset( $_POST['udb_content_type'] ) ) {
		update_post_meta( $post_id, 'udb_content_type', sanitize_text_field( $_POST['udb_content_type'] ) );
	}

	// HTML content.
	if ( isset( $_POST['udb_html_content'] ) ) {
		update_post_meta( $post_id, 'udb_html_content', wp_kses_post( $_POST['udb_html_content'] ) );
	}

	// Menu type.
	if ( isset( $_POST['udb_menu_type'] ) ) {
		update_post_meta( $post_id, 'udb_menu_type', sanitize_text_field( $_POST['udb_menu_type'] ) );
	}

	// Menu parent.
	if ( isset( $_POST['udb_menu_parent'] ) ) {
		update_post_meta( $post_id, 'udb_menu_parent', sanitize_text_field( $_POST['udb_menu_parent'] ) );
	}

	// Menu order.
	if ( isset( $_POST['udb_menu_order'] ) ) {
		update_post_meta( $post_id, 'udb_menu_order', sanitize_text_field( $_POST['udb_menu_order'] ) );
	}

	// Menu icon.
	if ( isset( $_POST['udb_menu_icon'] ) ) {
		update_post_meta( $post_id, 'udb_menu_icon', sanitize_text_field( $_POST['udb_menu_icon'] ) );
	}

	// Display page title.
	if ( isset( $_POST['udb_remove_page_title'] ) ) {
		update_post_meta( $post_id, 'udb_remove_page_title', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_page_title' );
	}

	// Add page margin.
	if ( isset( $_POST['udb_remove_page_margin'] ) ) {
		update_post_meta( $post_id, 'udb_remove_page_margin', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_page_margin' );
	}

	// Display admin notices.
	if ( isset( $_POST['udb_remove_admin_notices'] ) ) {
		update_post_meta( $post_id, 'udb_remove_admin_notices', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_admin_notices' );
	}

	// Custom css.
	if ( isset( $_POST['udb_custom_css'] ) ) {
		update_post_meta( $post_id, 'udb_custom_css', $module->content()->sanitize_css_content( $_POST['udb_custom_css'] ) );
	}

	do_action( 'udb_save_admin_page', $post_id );

};
