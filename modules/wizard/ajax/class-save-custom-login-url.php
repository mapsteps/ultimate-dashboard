<?php
/**
 * Save custom login url.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Wizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Save_Custom_Login_Url {

	/**
	 * The filled custom login url.
	 *
	 * @var array
	 */
	private $custom_login_url;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_wizard_save_custom_login_url', [ $this, 'handler' ] );

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
	private function validate() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_wizard_save_custom_login_url_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		$this->custom_login_url = isset( $_POST['custom_login_url'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_login_url'] ) ) : '';

	}

	/**
	 * Save the data.
	 */
	private function save() {

		// Save data to the 'udb_login_redirect' option.
		// Retrieve the existing settings or an empty array if not present.
		$existing_settings                   = get_option( 'udb_login_redirect', [] );
		$existing_settings['login_url_slug'] = $this->custom_login_url;

		update_option( 'udb_login_redirect', $existing_settings );

		// set setup_wizard_completed to true.
		update_option( 'udb_setup_wizard_completed', true );

		wp_send_json_success( __( 'Login redirect saved', 'ultimate-dashboard' ) );

	}


}
