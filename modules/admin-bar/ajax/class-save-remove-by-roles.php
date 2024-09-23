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
	 * The referrer where UDB was installed from.
	 *
	 * @var string
	 */
	private $roles;

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

		$nonce       = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$this->roles = isset( $_POST['roles'] ) ? $_POST['roles'] : [];

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_bar_save_remove_by_roles' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
		}

	}

	/**
	 * Save remove by roles to database.
	 */
	public function save() {

		// Validate and sanitize the roles data from the class property $this->roles
		if ( isset( $this->roles ) && is_array( $this->roles ) ) {
			$remove_by_roles = array_map( 'sanitize_text_field', $this->roles );
		} else {
			$remove_by_roles = array(); // Default value if not set or invalid
		}

		// Prepare the settings array to be saved
		$new_settings = array(
			'remove_by_roles' => $remove_by_roles,
		);

		// Save the settings using update_option
		update_option( 'admin_bar_settings', $new_settings );

		wp_send_json_success( __( 'Admin bar settings saved', 'ultimate-dashboard' ) );

	}

}
