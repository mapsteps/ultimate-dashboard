<?php
/**
 * Get menu & submenu.
 *
 * This class is not used currently.
 * But leave it here because in the future, if requested, it would be used for
 * "hide menu item for specific user(s)" functionality (inside a dropdown).
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to get menu & submenu.
 */
class Get_Users {

	/**
	 * Get menu & submenu.
	 */
	public function ajax() {

		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_bar_get_users' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
		}

		$capability = apply_filters( 'udb_settings_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ) );
		}

		$this->load_users();

	}

	/**
	 * Manually load menu & submenu.
	 */
	public function load_users() {

		$users = get_users();

		$select2_data = array();

		foreach ( $users as $user ) {
			array_push(
				$select2_data,
				array(
					'id'   => $user->ID,
					'text' => $user->display_name,
				)
			);
		}

		wp_send_json_success( $select2_data );

	}

}
