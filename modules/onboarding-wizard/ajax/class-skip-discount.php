<?php
/**
 * Skip discount.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class SkipDiscount {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_onboarding_wizard_skip_discount', [ $this, 'handler' ] );

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

		$capability = apply_filters( 'udb_modules_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_skip_discount_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

	}

	/**
	 * Save the data.
	 */
	private function skip_discount() {

		update_option( 'udb_onboarding_wizard_completed', 1 );
		wp_send_json_success( __( 'Discount skipped', 'ultimate-dashboard' ) );

	}

}
