<?php
/**
 * Save remove by roles.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to save remove by roles.
 */
class Save_Remove_By_Roles {

	/**
	 * The roles to remove the admin bar for.
	 *
	 * @var string[]
	 */
	private $roles = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_admin_bar_save_remove_by_roles', [ $this, 'handler' ] );

	}

	/**
	 * The request handler.
	 */
	public function handler() {

		$this->validate();
		$this->save();

	}

	/**
	 * Validate the data.
	 */
	public function validate() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_bar_save_remove_by_roles' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
		}

		$capability = apply_filters( 'udb_settings_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ) );
		}

		$roles = isset( $_POST['roles'] ) ? $_POST['roles'] : [];

		if ( ! is_array( $roles ) ) {
			wp_send_json_error( __( 'Roles should be an array', 'ultimate-dashboard' ) );
		}

		foreach ( $roles as $role ) {
			if ( empty( $role ) || ! is_string( $role ) ) {
				wp_send_json_error( __( 'Roles should be an array of strings', 'ultimate-dashboard' ) );
				break;
			}

			$this->roles[] = sanitize_text_field( $role );
		}

	}

	/**
	 * Save the data.
	 */
	public function save() {

		$settings = get_option( 'udb_settings', array() );

		$settings['remove_admin_bar'] = $this->roles;

		update_option( 'udb_settings', $settings );

		wp_send_json_success( __( 'Admin bar visibility settings saved', 'ultimate-dashboard' ) );

	}

}
