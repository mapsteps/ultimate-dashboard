<?php
/**
 * Skip discount.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\PluginOnboarding\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class SkipDiscount {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_plugin_onboarding_skip_discount', [ $this, 'handler' ] );

	}

	/**
	 * The request handler.
	 */
	public function handler() {

		$this->validate();
		$this->skip_discount();

	}

	/**
	 * Validate the data.
	 */
	private function validate() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_plugin_onboarding_skip_discount_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

	}

	/**
	 * Save the data.
	 */
	private function skip_discount() {

		delete_option( 'udb_migration_from_erident' );
		wp_send_json_success( __( 'Discount skipped', 'ultimate-dashboard' ) );

	}

}
